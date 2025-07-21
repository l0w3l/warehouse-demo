<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import { computed, onMounted, reactive, ref } from 'vue';
import { OrderCollection } from '@/store/api/DTO/Orders/OrderCollection';
import { useOrderStore } from '@/store/order';
import OrderItemModal from '@/components/Warehouse/Orders/OrderItemModal.vue';
import { isOrderStatus, Order, OrderSort, OrderStatus } from '@/store/api/DTO/Orders/Order';
import CreateOrderForm from '@/components/Warehouse/Orders/CreateOrderForm.vue';
import { ElMessage } from 'element-plus';

const createOrderDialog = reactive<{
    visible: boolean;
}>({
    visible: false,
});

const ordersDialog = reactive<{
    visible: boolean;
    orderId?: number;
}>({
    visible: false,
    orderId: undefined,
});

const data = reactive<{
    offset: number;
    limit: number;
    orderSort: OrderSort;
    orderFilter?: OrderStatus;
    orders: OrderCollection;
}>({
    offset: 0,
    limit: 10,
    orderFilter: undefined,
    orderSort: OrderSort.ID_ASC,
    orders: { count: 0, data: [] },
});

const loading = ref(true);

const orderStore = useOrderStore();

const paginationCallback = async (newOffset: number) => {
    try {
        data.offset = newOffset;
        await refreshOrdersData();
    } catch (error) {
        console.error('Ошибка загрузки:', error);
    }
};

const rowClickHandler = async (row: { id: number }): Promise<any> => {
    ordersDialog.orderId = row.id;
    ordersDialog.visible = true;
};

const acceptOrder = async (order: Order) => {
    await orderStore.completeOrder(order);
    ElMessage.success({
        message: `Order #${order.id} was accepted!`
    });
    await refreshOrdersData();
};

const cancelOrder = async (order: Order) => {
    await orderStore.cancelOrder(order);
    ElMessage.error({
        message: `Order #${order.id} was cancelled!`
    });
    await refreshOrdersData();
};

const restoreOrder = async (order: Order) => {
    await orderStore.restoreOrder(order);
    ElMessage.warning({
        message: `Order #${order.id} was restored!`
    });
    await refreshOrdersData();
};

const refreshOrdersData = async () => {
    loading.value = true;
    data.orders = await orderStore.fetchOrders(data.offset, data.limit, data.orderSort, data.orderFilter ?? null);
    loading.value = false;
};

onMounted(async function () {
    await refreshOrdersData();
});

const modalOrder = computed<Order | undefined>(() => {
    return ordersDialog.orderId !== undefined ? (data.orders.data.find((order) => order.id === ordersDialog.orderId) ?? undefined) : undefined;
});

const handleSort = async ({ prop, order }: { prop: string; order: 'ascending' | 'descending' | null }) => {
    if (order) {
        if (prop === 'updated_at.date' || prop === 'created_at.date') {
            prop = prop.split('.')[0];
        }
        const sortName = `${prop}_`.concat(order === 'ascending' ? 'asc' : 'desc');
        console.log(sortName);

        data.orderSort = sortName as OrderSort;
    } else {
        data.orderSort = OrderSort.ID_ASC;
    }

    await refreshOrdersData();
};

const handleFilter = async (filters: Record<string, string[]>) => {
    let cleanedFilter: string = '';
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    Object.entries(filters).forEach(([_, value]) => {
        if (value && value.length) {
            cleanedFilter = value[0];
        }
    });

    if (isOrderStatus(cleanedFilter)) {
        data.orderFilter = cleanedFilter;
    }

    data.offset = 0;
    await refreshOrdersData();
};
</script>

<template>
    <div v-loading="loading" class="flex h-screen flex-col justify-center">
        <div class="flex justify-end px-4 py-2 align-top">
            <el-button type="primary" @click="createOrderDialog.visible = true">Create</el-button>
            <el-dialog v-model="createOrderDialog.visible" width="800">
                <CreateOrderForm @success="createOrderDialog.visible = false" />
            </el-dialog>
        </div>
        <div class="overflow-auto align-middle">
            <el-table
                height="694"
                :data="data.orders.data"
                @sort-change="handleSort"
                @filter-change="handleFilter"
                class="flex justify-center"
                @row-click="rowClickHandler"
            >
                <el-table-column prop="id" label="Id" width="70" sortable="custom" />
                <el-table-column prop="customer" label="Customer" width="100" align="center" sort />
                <el-table-column prop="total_amount" label="Amount" width="120" sortable="custom" align="center" />
                <el-table-column align="center" label="Quantity" width="120" prop="total_quantity" sortable="custom" />
                <el-table-column
                    prop="status"
                    label="Status"
                    align="center"
                    width="120"
                    :filters="[
                        { text: 'completed', value: 'completed' },
                        { text: 'cancelled', value: 'cancelled' },
                        { text: 'active', value: 'active' },
                    ]"
                    :filter-multiple="false"
                />
                <el-table-column
                    align="center"
                    label="Created At"
                    width="150"
                    prop="created_at.date"
                    :formatter="(row, column, cellValue: string, index) => new Date(cellValue).toUTCString()"
                    sortable="custom"
                />
                <el-table-column
                    align="center"
                    label="Updated At"
                    width="150"
                    prop="updated_at.date"
                    sortable="custom"
                    :formatter="(row, column, cellValue: string, index) => new Date(cellValue).toUTCString()"
                />
            </el-table>
        </div>

        <div class="flex justify-center align-bottom">
            <Pagination class="" :offset="data.offset" :limit="data.limit" :count="data.orders.count" @update:offset="paginationCallback" />
        </div>
    </div>

    <OrderItemModal v-model="ordersDialog.visible" :order="modalOrder" @accept="acceptOrder" @cancel="cancelOrder" @restore="restoreOrder" />
</template>

<style scoped></style>
