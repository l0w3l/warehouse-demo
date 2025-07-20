<script setup lang="ts">

import Pagination from '@/components/Pagination.vue';
import { StockChangeCollection } from '@/store/api/DTO/StockHistory/StockChangeCollection';
import { useWarehouseStore } from '@/store/warehouse';
import { onMounted, reactive } from 'vue';

const data = reactive<{
    offset: number;
    limit: number;
    stockChanges: StockChangeCollection;
}>({
    offset: 0,
    limit: 10,
    stockChanges: { count: 0, data: [] },
});

const warehousesStore = useWarehouseStore();

const paginationCallback = async (newOffset: number) => {
    try {
        data.offset = newOffset;
        await refreshStockHistoryData();
    } catch (error) {
        console.error('Ошибка загрузки:', error);
    }
};

const refreshStockHistoryData = async () => {
    data.stockChanges = await warehousesStore.fetchStockHistory(data.offset, data.limit);
}

onMounted(async function () {
    await refreshStockHistoryData()
});

</script>

<template>
    <div class="overflow-auto align-middle">
        <el-table :data="data.stockChanges.data" class="justify-self-start">
            <el-table-column prop="id" label="Id" width="70" />
            <el-table-column prop="stock.product.name" label="Product" align="center" sort/>
            <el-table-column prop="stock.warehouse.name" label="Warehouse" align="center" sort/>
            <el-table-column prop="change" label="Change" align="center" sort/>
        </el-table>
    </div>

    <div class="flex justify-center align-bottom">
        <Pagination
            class=""
            :offset="data.offset"
            :limit="data.limit"
            :count="data.stockChanges.count"
            @update:offset="paginationCallback"
        />
    </div>
</template>

<style scoped>

</style>
