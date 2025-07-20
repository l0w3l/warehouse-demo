import { LaravelDateTime } from '@/store/api/DTO/LaravelDateTime';
import { WarehouseItem } from '@/store/api/DTO/Warehouses/WarehouseItem';
import { OrderProduct } from '@/store/api/DTO/Orders/OrderProduct';

/**
 * Типы сортировок
 */
export enum OrderFilter {
    COMPLETED_AT_DESC = 'completed_at_desc',
    COMPLETED_AT_ASC = 'completed_at_asc',
    CREATED_AT_DESC = 'created_at_desc',
    CREATED_AT_ASC = 'created_at_asc',
    UPDATED_AT_DESC = 'updated_at_desc',
    UPDATED_AT_ASC = 'updated_at_asc',
    TOTAL_PRICE_DESC = 'total_amount_desc',
    TOTAL_AMOUNT_ASC = 'total_amount_asc',
    TOTAL_QUANTITY_DESC = 'total_quantity_desc',
    TOTAL_QUANTITY_ASC = 'total_quantity_asc'
}

/**
 * Статусы заказа
 */
export type OrderStatus = 'completed' | 'cancelled' | 'active';

/**
 * Основной интерфейс заказа
 */
export interface Order {
    id: number;
    customer: string;
    total_amount: number;
    total_quantity: number;
    status: OrderStatus;
    completed_at: LaravelDateTime | null;
    warehouse: WarehouseItem;
    products: OrderProduct[];
    created_at: LaravelDateTime;
    updated_at: LaravelDateTime;
}
