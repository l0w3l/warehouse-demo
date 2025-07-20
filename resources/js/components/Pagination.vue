<template>
    <el-pagination
        v-model:current-page="currentPage"
        :page-size="limit"
        :total="count"
        :background="true"
        layout="prev, pager, next, jumper, ->, total"
        class="mt-4 justify-end"
    />
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
    offset: {
        type: Number,
        required: true,
    },
    limit: {
        type: Number,
        required: true,
    },
    count: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits<{
    (e: 'update:offset', newOffset: number): void
}>();

// Вычисляем текущую страницу
const currentPage = computed({
    get: () => Math.floor(props.offset / props.limit) + 1,
    set: (val) => {
        const newOffset = (val - 1) * props.limit;
        emit('update:offset', newOffset);
    }
});
</script>
