import { Order } from '@/store/api/DTO/Orders/Order';

/**
 * Интерфейс ответа API со списком заказов
 */
export interface OrderCollection {
    data: Order[];
}
