import { LaravelDateTime } from '@/store/api/DTO/LaravelDateTime';
import { Stock } from '@/store/api/DTO/StockHistory/Stock';

/**
 * Описывает изменение остатка
 */
export interface StockChange {
    id: string;
    change: string; // Строка, так как может содержать "+" или "-"
    stock: Stock;
    created_at: LaravelDateTime;
    updated_at: LaravelDateTime;
}
