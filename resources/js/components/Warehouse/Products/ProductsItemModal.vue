<script setup lang="ts">
import { FullProduct } from '@/store/api/DTO/Products/FullProduct';

const props = defineProps<{
    product?: FullProduct,
}>();

const visible = defineModel<boolean>();
</script>

<template>
    <el-dialog
        v-if="props.product"
        v-model="visible"
        :title="`Product #${props.product.id} (${props.product.name})`"
        width="800"
    >
        <div class="flex flex-col gap-6">
            <el-table
                :data="props.product.warehouses"
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
                    property="stock"
                    label="Stock"
                    sortable
                    width="120"
                    align="center"
                    header-align="center"
                />
            </el-table>
        </div>
    </el-dialog>
</template>
