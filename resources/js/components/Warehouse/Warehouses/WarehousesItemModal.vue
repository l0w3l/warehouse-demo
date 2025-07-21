<script setup lang="ts">
import { WarehouseProduct } from '@/store/api/DTO/Warehouses/Products/WarehouseProduct';
import {  reactive, watch } from 'vue';
import { useWarehouseStore } from '@/store/warehouse';
import { WarehouseItem } from '@/store/api/DTO/Warehouses/WarehouseItem';
import Pagination from '@/components/Pagination.vue';

const data = reactive<{
    offset: number,
    limit: number,
    count: number,
    products: WarehouseProduct[]
}>({
    offset: 0,
    limit: 10,
    count: 0,
    products: []
});

const warehouse = defineModel<WarehouseItem|undefined>('warehouse');
const visible = defineModel<boolean>();
const warehouseStore = useWarehouseStore();

watch<WarehouseItem|undefined>(warehouse, async (before, after) => {
    if (after !== undefined) {

        loadProducts(after);
    } else {
        data.products = [];
    }
});

const updateOffset = async (newOffset: number) => {
    if (warehouse.value !== undefined) {
        await loadProducts(warehouse.value, newOffset);
    }
};

const loadProducts = async (warehouse: WarehouseItem, offset: number = 0, limit: number = 10) => {
    const warehouseProducts = await warehouseStore.fetchWarehouseProducts(warehouse, offset, limit);

    data.products = warehouseProducts.data.products;
    data.count = warehouseProducts.count;
    data.offset = offset;
};

</script>

<template>
    <el-dialog
        v-if="warehouse"
        v-model="visible"
        :title="`Warehouse #${warehouse.id} (${warehouse.name})`"
        width="800"
    >
        <div class="flex flex-col justify-center gap-6">
            <el-table
                :data="data.products"
                class="w-full"
                stripe
                border
            >
                <el-table-column
                    property="id"
                    label="Id"
                    width="70"
                    sortable
                />
                <el-table-column
                    property="name"
                    label="Name"
                    align="center"
                    header-align="center"
                />
                <el-table-column
                    property="price"
                    label="Price"
                    align="center"
                    header-align    ="center"
                />
                <el-table-column
                    property="stock"
                    label="Stock"
                    sortable
                    width="120"
                    align="center"
                    header-align="center"
                />
            </el-table>

            <div class="flex justify-center">
                <Pagination @update:offset="updateOffset" :count="data.count" :limit="data.limit" :offset="data.offset" />
            </div>
        </div>
    </el-dialog>
</template>
