<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import WarehousesTable from '@/components/Warehouse/Warehouses/WarehousesTable.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Orders',
        href: '/orders',
    },
];

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid h-screen grid-rows-[auto_1fr_auto] overflow-x-auto rounded-xl p-4">
            <WarehousesTable />
        </div>
    </AppLayout>
</template>
