import { WarehouseItem } from '@/store/api/DTO/Warehouses/WarehouseItem';
import { ProductItem } from '@/store/api/DTO/Products/ProductItem';

/**
 * Описывает связку товара и склада
 */
export interface Stock {
    warehouse: WarehouseItem;
    product: ProductItem;
}
