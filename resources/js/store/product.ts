import { defineStore } from 'pinia';
import { V1 } from '@/store/api';
import { ProductCollection } from '@/store/api/DTO/Products/ProductCollection';

export const useProductStore = defineStore('product', {
    actions: {
        // Получить список товаров
        async fetchProducts(offset = 0, limit = 10): Promise<ProductCollection> {
            return await V1.products.index(offset, limit);
        }
    }
});
