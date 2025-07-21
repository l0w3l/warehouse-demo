<script setup lang="ts">

import Pagination from '@/components/Pagination.vue';
import { computed, onMounted, reactive, ref } from 'vue';
import { useWarehouseStore } from '@/store/warehouse';
import { WarehouseCollection } from '@/store/api/DTO/Warehouses/WarehouseCollection';
import { WarehouseItem } from '@/store/api/DTO/Warehouses/WarehouseItem';
import WarehousesItemModal from '@/components/Warehouse/Warehouses/WarehousesItemModal.vue';

const warehousesDialog = reactive<{
    visible: boolean;
    warehouseId?: number;
}>({
    visible: false,
    warehouseId: undefined,
});

const data = reactive<{
    offset: number;
    limit: number;
    warehouses: WarehouseCollection;
}>({
    offset: 0,
    limit: 10,
    warehouses: { count: 0, data: [] },
});

const loading = ref<boolean>(true);
const warehousesStore = useWarehouseStore();

const paginationCallback = async (newOffset: number) => {
    try {
        data.offset = newOffset;
        await refreshWarehousesData();
    } catch (error) {
        console.error('Ошибка загрузки:', error);
    }
};

const rowClickHandler = async (row: { id: number }): Promise<any> => {
    warehousesDialog.warehouseId = row.id;
    warehousesDialog.visible = true;
};

const refreshWarehousesData = async () => {
    loading.value = true;
    data.warehouses = await warehousesStore.fetchWarehouses(data.offset, data.limit);
    loading.value = false;
}

onMounted(async function () {
    await refreshWarehousesData()
});

const modalWarehouse = computed<WarehouseItem|undefined>(() => {
    return (warehousesDialog.warehouseId !== undefined) ? data.warehouses.data.find((product) => product.id === warehousesDialog.warehouseId) ?? undefined: undefined;
})
</script>

<template>
    <div v-loading="loading" class="flex h-screen flex-col justify-center">
        <div class="overflow-auto align-middle">
            <el-table :data="data.warehouses.data" class="flex justify-center" height="394" @row-click="rowClickHandler">
                <el-table-column prop="id" label="Id" width="70" />
                <el-table-column prop="name" label="Name" align="center" width="300"/>
            </el-table>
        </div>

        <div class="flex justify-center align-bottom">
            <Pagination
                class=""
                :offset="data.offset"
                :limit="data.limit"
                :count="data.warehouses.count"
                @update:offset="paginationCallback"
            />
        </div>
    </div>

    <WarehousesItemModal
        v-model="warehousesDialog.visible"
        v-model:warehouse="modalWarehouse"
    />
</template>

<style scoped>

</style>
