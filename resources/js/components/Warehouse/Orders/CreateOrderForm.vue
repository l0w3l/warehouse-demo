<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { ElForm, ElFormItem, ElInput, ElSelect, ElOption, ElButton, ElMessage } from 'element-plus';
import type { FormInstance, FormRules } from 'element-plus';
import { CreateOrderTransfer } from '@/store/api/DTO/Orders/Transfer/CreateOrderTransfer';
import { WarehouseCollection } from '@/store/api/DTO/Warehouses/WarehouseCollection';
import { WarehouseProductCollection } from '@/store/api/DTO/Warehouses/Products/WarehouseProductCollection';
import { useWarehouseStore } from '@/store/warehouse';
import { WarehouseItem } from '@/store/api/DTO/Warehouses/WarehouseItem';
import { WarehouseProduct } from '@/store/api/DTO/Warehouses/Products/WarehouseProduct';
import { useOrderStore } from '@/store/order';

const data = reactive<{
    warehouses: WarehouseCollection,
    warehouseProducts?: WarehouseProductCollection
}>({
    warehouses: {
        count: 0,
        data: [],
    },
    warehouseProducts: undefined,
});

const emit = defineEmits<{
    (e: 'success'): void
}>();

const formRef = ref<FormInstance>();
const formData = ref<{
    customer: string,
    warehouse: WarehouseItem
}>({
    customer: '',
    warehouse: {},
});

const selectedProducts = ref<WarehouseProduct[]>([]);
const quantityMap = ref<Record<number, number>>({});

const warehouseStore = useWarehouseStore();
const orderStore = useOrderStore();

const rules = ref<FormRules>({
    customer: [
        { required: true, message: 'Please enter name of client', trigger: 'blur' },
        { max: 255, message: 'Max length is 255', trigger: 'blur' },
    ],
    warehouse: [{ required: true, message: 'Please, select warehouse', trigger: 'change' }],
});

const warehouseSelectHandle = async (warehouse: WarehouseItem) => {
    data.warehouseProducts = await warehouseStore.fetchWarehouseProducts(warehouse, 0, 9999);

    selectedProducts.value = [];
    quantityMap.value = {};
}

const productSelectHandler = (product: WarehouseProduct) => {
    if (!selectedProducts.value.find((element: WarehouseProduct) => element.id === product.id)) {
        selectedProducts.value.push(product);

        quantityMap.value[product.id] = 1;
    }
};

const removeProduct = (productId: number) => {
    selectedProducts.value = selectedProducts.value.filter((p) => p.id !== productId);
    delete quantityMap.value[productId];
};

const validateForm = async () => {
    if (!formRef.value) return false;

    try {
        await formRef.value.validate();
        return true;
    } catch {
        ElMessage.error('Please fill the fields');
        return false;
    }
};

const validateProducts = () => {
    if (selectedProducts.value.length === 0) {
        ElMessage.error('Add at least one product');
        return false;
    }
    return true;
};

const handleSubmit = async () => {
    const isFormValid = await validateForm();
    const areProductsValid = validateProducts();

    if (!isFormValid || !areProductsValid) return;

    const createOrder: CreateOrderTransfer = {
        customer: formData.value.customer,
        warehouse_id: formData.value.warehouse.id,
        products: selectedProducts.value.map((warehouseProduct: WarehouseProduct) => ({
            id: warehouseProduct.id,
            quantity: quantityMap.value[warehouseProduct.id]
        })),
    };

    await orderStore.createOrder(createOrder);

    ElMessage.success({
        message: 'Order created successfully!',
        type: 'success',
    });
    emit('success');
};

const resetForm = () => {
    formRef.value?.resetFields();
    selectedProducts.value = [];
    quantityMap.value = {};
};

onMounted(async () => {
    data.warehouses = await warehouseStore.fetchWarehouses(0, 9999);
});
</script>

<template>
    <el-form ref="formRef" :model="formData" :rules="rules" label-position="top" class="order-form">
        <el-form-item label="Customer" prop="customer">
            <el-input v-model="formData.customer" placeholder="Введите имя клиента" clearable />
        </el-form-item>

        <el-form-item label="Warehouse" prop="warehouse_id">
            <el-select v-model="formData.warehouse" placeholder="Выберите склад" clearable @change="warehouseSelectHandle" class="w-full">
                <el-option v-for="warehouse in data.warehouses.data" :key="warehouse.id" :label="warehouse.name" :value="warehouse" />
            </el-select>
        </el-form-item>


        <div class="products-section">
            <h3 class="section-title">Products</h3>

            <div class="product-selector mb-4">
                <el-select placeholder="Chose product" clearable class="mr-2" @change="productSelectHandler">
                    <el-option v-for="product in data.warehouseProducts?.data.products" :key="product.id" :label="product.name" :value="product" />
                </el-select>
            </div>

            <div class="product-list">
                <div v-for="product in selectedProducts" :key="product.id" class="product-item">
                    <div class="product-info">
                        <span>{{ product.name }}</span>
                        <span class="price">Price: {{ Math.ceil(product.price * quantityMap[product.id]) }} $</span>
                    </div>

                    <div class="product-actions">
                        <el-input-number v-model="quantityMap[product.id]" :min="1" :max="product.stock" size="small" class="quantity-input" />

                        <el-button type="danger" sizproductse="small" circle @click="removeProduct(product.id)">
                            <i class="el-icon-delete" />
                        </el-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <el-button type="primary" @click="handleSubmit"> Create order </el-button>

            <el-button @click="resetForm"> Clear </el-button>
        </div>
    </el-form>
</template>

<style scoped>
.order-form {
    max-width: 600px;
    margin: 0 auto;
}

.section-title {
    margin: 20px 0 10px;
    font-size: 16px;
    color: var(--el-text-color-primary);
}

.product-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    margin-bottom: 8px;
    border: 1px solid var(--el-border-color-light);
    border-radius: 4px;
}

.product-info {
    display: flex;
    flex-direction: column;
}

.price {
    font-size: 12px;
    color: var(--el-text-color-secondary);
}

.product-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.quantity-input {
    width: 100px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
}
</style>
