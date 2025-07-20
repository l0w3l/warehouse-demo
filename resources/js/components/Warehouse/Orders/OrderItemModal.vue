<script setup lang="ts">
import { Order } from '@/store/api/DTO/Orders/Order';
import { useOrderStore } from '@/store/order';

const props = defineProps<{
    order?: Order,
}>();

const emit = defineEmits<{
    (e: 'accept', order: Order): void
    (e: 'cancel', order: Order): void
    (e: 'restore', order: Order): void
}>();

const visible = defineModel<boolean>();
</script>

<template>
    <el-dialog
        v-if="props.order"
        v-model="visible"
        :title="`Order #${props.order.id} (${props.order.customer}) #${props.order.status.toUpperCase()}`"
        width="800"
    >
        <div class="flex flex-col gap-6">
            <el-table
                :data="props.order.products"
                class="w-full"
                stripe
                border
            >
                <el-table-column
                    property="name"
                    label="Name"
                    width="200"
                    header-align="center"
                />
                <el-table-column
                    property="price"
                    label="Price"
                    sortable
                    align="center"
                    header-align="center"
                />
                <el-table-column
                    property="quantity"
                    label="Quantity"
                    sortable
                    width="120"
                    align="center"
                    header-align="center"
                />
            </el-table>

            <div class="flex justify-between gap-4">
                <el-button
                    type="success"
                    :disabled="props.order?.status !== 'active'"
                    class="flex-1"
                    @click="emit('accept', props.order)"
                >
                    Accept
                </el-button>
                <el-button
                    type="danger"
                    :disabled="props.order?.status !== 'active'"
                    class="flex-1"
                    @click="emit('cancel', props.order)"
                >
                    Cancel
                </el-button>
                <el-button
                    type="warning"
                    :disabled="props.order?.status !== 'cancelled'"
                    class="flex-1"
                    @click="emit('restore', props.order)"
                >
                    Restore
                </el-button>
            </div>
        </div>
    </el-dialog>
</template>
