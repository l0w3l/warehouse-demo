<script setup lang="ts">

import Pagination from '@/components/Pagination.vue';
import { onMounted, reactive } from 'vue';
import { OrderCollection } from '@/store/api/DTO/Orders/OrderCollection';
import { useOrderStore } from '@/store/order';
import OrderItemModal from '@/components/Warehouse/Orders/OrderItemModal.vue';
import { Order } from '@/store/api/DTO/Orders/Order';

const productsDialog = reactive<{
    visible: boolean;
    orderId?: number;
}>({
    visible: false,
    orderId: undefined,
});

const data = reactive<{
    offset: number;
    limit: number;
    orders: OrderCollection;
}>({
    offset: 0,
    limit: 10,
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
    productsDialog.orderId = row.id;
    productsDialog.visible = true;
};

const acceptOrder = async (order: Order) => {
    await orderStore.completeOrder(order);
    await refreshOrdersData();
}

const cancelOrder = async (order: Order) => {
    await orderStore.cancelOrder(order);
    await refreshOrdersData();
}

const restoreOrder = async (order: Order) => {
    await orderStore.restoreOrder(order);
    await refreshOrdersData();
}

const refreshOrdersData = async () => {
    data.orders = await orderStore.fetchOrders(data.offset, data.limit);
}

onMounted(async function () {
    data.orders = await orderStore.fetchOrders(data.offset, data.limit);
});
</script>

<template>
    <div class="overflow-auto align-middle">
        <el-table :data="data.orders.data" class="justify-self-start" @row-click="rowClickHandler">
            <el-table-column prop="id" label="Id" width="50" />
            <el-table-column prop="customer" label="Customer" align="center" sort/>
            <el-table-column prop="total_amount" label="Amount" align="center" />
            <el-table-column prop="total_quantity" label="Quantity" align="center" />
            <el-table-column prop="status" label="Status" align="center" />
        </el-table>
    </div>

    <div class="flex justify-center align-bottom">
        <Pagination
            class=""
            :offset="data.offset"
            :limit="data.limit"
            :count="data.orders.count"
            :callback="paginationCallback"
            @update:offset="(newOffset) => (data.offset = newOffset)"
        />
    </div>

    <OrderItemModal
        v-model="productsDialog.visible"
        :order="(productsDialog.orderId !== undefined) ? data.orders.data.find((order) => order.id === productsDialog.orderId) ?? undefined: undefined"
        @accept="acceptOrder"
        @cancel="cancelOrder"
        @restore="restoreOrder"
    />
</template>

<style scoped>

</style>
