import { StockChange } from '@/store/api/DTO/StockHistory/StockChange';

/**
 * Интерфейс ответа API со списком изменений остатков
 */
export interface StockChangeCollection {
    data: StockChange[];
}
