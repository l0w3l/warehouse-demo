<script setup lang="ts">

import Pagination from '@/components/Pagination.vue';
import { computed, onMounted, reactive } from 'vue';
import { useProductStore } from '@/store/product';
import { ProductCollection } from '@/store/api/DTO/Products/ProductCollection';
import ProductsItemModal from '@/components/Warehouse/Products/ProductsItemModal.vue';
import { Order } from '@/store/api/DTO/Orders/Order';
import { FullProduct } from '@/store/api/DTO/Products/FullProduct';

const productsDialog = reactive<{
    visible: boolean;
    productId?: number;
}>({
    visible: false,
    productId: undefined,
});

const data = reactive<{
    offset: number;
    limit: number;
    products: ProductCollection;
}>({
    offset: 0,
    limit: 10,
    products: { count: 0, data: [] },
});

const productStore = useProductStore();

const paginationCallback = async (newOffset: number) => {
    try {
        data.offset = newOffset;
        await refreshProductsData();
    } catch (error) {
        console.error('Ошибка загрузки:', error);
    }
};

const rowClickHandler = async (row: { id: number }): Promise<any> => {
    productsDialog.productId = row.id;
    productsDialog.visible = true;
};

const refreshProductsData = async () => {
    data.products = await productStore.fetchProducts(data.offset, data.limit);
}

onMounted(async function () {
    await refreshProductsData()
});

const modalProduct = computed<FullProduct|undefined>(() => {
    return (productsDialog.productId !== undefined) ? data.products.data.find((product) => product.id === productsDialog.productId) ?? undefined: undefined;
})
</script>

<template>
    <div class="overflow-auto align-middle">
        <el-table :data="data.products.data" class="justify-self-start" @row-click="rowClickHandler">
            <el-table-column prop="id" label="Id" width="50" />
            <el-table-column prop="name" label="Name" align="center" sort/>
            <el-table-column prop="price" label="Price" align="center" />
        </el-table>
    </div>

    <div class="flex justify-center align-bottom">
        <Pagination
            class=""
            :offset="data.offset"
            :limit="data.limit"
            :count="data.products.count"
            @update:offset="paginationCallback"
        />
    </div>

    <ProductsItemModal
        v-model="productsDialog.visible"
        :product="modalProduct"
    />
</template>

<style scoped>

</style>
