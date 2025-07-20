import { WarehouseItem } from '@/store/api/DTO/Warehouses/WarehouseItem';

/**
 * Интерфейс для ответа API со списком складов
 */
export interface WarehouseCollection {
    data: WarehouseItem[];
}
