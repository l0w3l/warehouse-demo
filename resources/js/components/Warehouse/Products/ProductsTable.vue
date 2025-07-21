<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import { computed, onMounted, reactive, ref } from 'vue';
import { useProductStore } from '@/store/product';
import { ProductCollection } from '@/store/api/DTO/Products/ProductCollection';
import ProductsItemModal from '@/components/Warehouse/Products/ProductsItemModal.vue';
import { FullProduct } from '@/store/api/DTO/Products/FullProduct';
import { WarehouseStock } from '@/store/api/DTO/Products/WarehouseStock';

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

const loading = ref<boolean>(true);
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
    loading.value = true;
    data.products = await productStore.fetchProducts(data.offset, data.limit);
    loading.value = false;
};

onMounted(async function () {
    await refreshProductsData();
});

const modalProduct = computed<FullProduct | undefined>(() => {
    return productsDialog.productId !== undefined
        ? (data.products.data.find((product) => product.id === productsDialog.productId) ?? undefined)
        : undefined;
});
</script>

<template>
    <div v-loading="loading" class="flex h-screen flex-col justify-center">
        <div class="overflow-auto align-middle">
            <el-table height="494" class="flex justify-center" :data="data.products.data" @row-click="rowClickHandler">
                <el-table-column prop="id" label="Id" width="50" />
                <el-table-column prop="name" label="Name" align="center" width="300" />
                <el-table-column prop="price" label="Price" align="center" width="100" />
                <el-table-column
                    prop="warehouses"
                    :formatter="
                        (row, column, cellValue: WarehouseStock[], index) =>
                            cellValue.reduce((carry, value: WarehouseStock) => carry + value.stock, 0).toString()
                    "
                    label="Stock"
                    align="center"
                    width="100"
                />
            </el-table>
        </div>

        <div class="flex justify-center align-bottom">
            <Pagination class="" :offset="data.offset" :limit="data.limit" :count="data.products.count" @update:offset="paginationCallback" />
        </div>
    </div>

    <ProductsItemModal v-model="productsDialog.visible" :product="modalProduct" />
</template>

<style scoped></style>
