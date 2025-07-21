import { OrderProductItem } from '@/store/api/DTO/Orders/Transfer/OrderProductItem';

/**
 * DTO для создания/обновления заказа
 */
export interface UpdateOrderTransfer {
    customer?: string;       // required|string|max:255
    completed_at?: string;  // nullable|date (ISO string format)
    warehouse_id: number;   // required|integer|exists:warehouses,id
    products: OrderProductItem[]; // массив с валидными продуктами
}
