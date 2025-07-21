import { defineStore } from 'pinia';
import { V1 } from '@/store/api';
import { WarehouseItem } from '@/store/api/DTO/Warehouses/WarehouseItem';
import { WarehouseProductCollection } from '@/store/api/DTO/Warehouses/Products/WarehouseProductCollection';
import { StockChangeCollection } from '@/store/api/DTO/StockHistory/StockChangeCollection';
import { WarehouseCollection } from '@/store/api/DTO/Warehouses/WarehouseCollection';

export const useWarehouseStore = defineStore('warehouse', {
    actions: {
        // Получить список складов
        async fetchWarehouses(offset = 0, limit = 10): Promise<WarehouseCollection> {
            return await V1.warehouses.index(offset, limit);
        },

        // Получить историю изменений складов
        async fetchStockHistory(offset = 0, limit = 10): Promise<StockChangeCollection> {
            return await V1.warehouses.history(offset, limit);
        },

        // Получить товары на конкретном складе
        async fetchWarehouseProducts(warehouse: WarehouseItem, offset = 0, limit = 10): Promise<WarehouseProductCollection> {
            return await V1.warehouses.products.index(warehouse, offset, limit);
        }
    }
});
