<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import { computed, onMounted, reactive } from 'vue';
import { OrderCollection } from '@/store/api/DTO/Orders/OrderCollection';
import { useOrderStore } from '@/store/order';
import OrderItemModal from '@/components/Warehouse/Orders/OrderItemModal.vue';
import { isOrderStatus, Order, OrderSort, OrderStatus } from '@/store/api/DTO/Orders/Order';

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
    await refreshOrdersData();
};

const cancelOrder = async (order: Order) => {
    await orderStore.cancelOrder(order);
    await refreshOrdersData();
};

const restoreOrder = async (order: Order) => {
    await orderStore.restoreOrder(order);
    await refreshOrdersData();
};

const refreshOrdersData = async () => {
    data.orders = await orderStore.fetchOrders(data.offset, data.limit, data.orderSort, data.orderFilter);
};

onMounted(async function () {
    await refreshOrdersData();
});

const modalOrder = computed<Order | undefined>(() => {
    return ordersDialog.orderId !== undefined ? (data.orders.data.find((order) => order.id === ordersDialog.orderId) ?? undefined) : undefined;
});

const handleSort = async ({ prop, order }: { prop: string; order: 'ascending' | 'descending' | null }) => {
    console.log(order);
    if (order) {
        const sortName = `${prop}_`.concat(order === 'ascending' ? 'asc' : 'desc');

        data.orderSort = sortName as OrderSort;
    } else {
        data.orderSort = OrderSort.ID_ASC;
    }

    await refreshOrdersData();
};

const handleFilter = async (filters: Record<string, string[]>) => {
    let cleanedFilter: string = '';
    Object.entries(filters).forEach(([_, value]) => {
        if (value && value.length) {
            cleanedFilter = value[0];
        }
    })

    if (isOrderStatus(cleanedFilter)) {
        data.orderFilter = cleanedFilter;
    }

    data.offset = 0;
    await refreshOrdersData();
}
</script>

<template>
    <div class="overflow-auto align-middle">
        <el-table :data="data.orders.data" @sort-change="handleSort" @filter-change="handleFilter" class="justify-self-start" @row-click="rowClickHandler">
            <el-table-column prop="id" label="Id" width="50" sortable="custom" />
            <el-table-column prop="customer" label="Customer" align="center" sort />
            <el-table-column prop="total_amount" label="Amount" sortable="custom" align="center" />
            <el-table-column align="center" label="Quantity" prop="total_quantity" sortable="custom" />
            <el-table-column
                prop="status"
                label="Status"
                align="center"
                :filters="[{ text: 'completed', value: 'completed' }, { text: 'cancelled', value: 'cancelled' }, { text: 'active', value: 'active' }]"
                :filter-multiple="false"
            />
        </el-table>
    </div>

    <div class="flex justify-center align-bottom">
        <Pagination class="" :offset="data.offset" :limit="data.limit" :count="data.orders.count" @update:offset="paginationCallback" />
    </div>

    <OrderItemModal v-model="ordersDialog.visible" :order="modalOrder" @accept="acceptOrder" @cancel="cancelOrder" @restore="restoreOrder" />
</template>

<style scoped></style>
