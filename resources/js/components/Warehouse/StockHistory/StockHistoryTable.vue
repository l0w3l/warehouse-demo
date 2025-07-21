<script setup lang="ts">

import Pagination from '@/components/Pagination.vue';
import { StockChangeCollection } from '@/store/api/DTO/StockHistory/StockChangeCollection';
import { useWarehouseStore } from '@/store/warehouse';
import { onMounted, reactive, ref } from 'vue';

const data = reactive<{
    offset: number;
    limit: number;
    stockChanges: StockChangeCollection;
}>({
    offset: 0,
    limit: 10,
    stockChanges: { count: 0, data: [] },
});

const loading = ref<boolean>(true);
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
    loading.value = true;
    data.stockChanges = await warehousesStore.fetchStockHistory(data.offset, data.limit);
    loading.value = false;
}

onMounted(async function () {
    await refreshStockHistoryData()
});

</script>

<template>
    <div v-loading="loading" class="flex h-screen flex-col justify-center">
        <div class="overflow-auto align-middle">
            <el-table :data="data.stockChanges.data" class="flex justify-center" height="694">
                <el-table-column prop="id" label="Id" width="70" />
                <el-table-column prop="stock.product.name" label="Product" align="center" width="200"/>
                <el-table-column prop="stock.warehouse.name" label="Warehouse" align="center" width="200"/>
                <el-table-column prop="change" label="Change" align="center" width="100"/>
                <el-table-column
                    align="center"
                    label="Created At"
                    width="150"
                    prop="created_at.date"
                    :formatter="(row, column, cellValue: string, index) => new Date(cellValue).toUTCString()"
                />
                <el-table-column
                    align="center"
                    label="Updated At"
                    width="150"
                    prop="updated_at.date"
                    :formatter="(row, column, cellValue: string, index) => new Date(cellValue).toUTCString()"
                />
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
    </div>
</template>

<style scoped>

</style>
