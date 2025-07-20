/**
 * Описывает товар в заказе
 */
export interface OrderProduct {
    id: number;
    name: string;
    price: number;
    quantity: number;
    created_at: string; // ISO format
    updated_at: string; // ISO format
}


