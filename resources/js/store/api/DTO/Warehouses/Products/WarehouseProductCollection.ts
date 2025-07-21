import { WarehouseDetails } from '@/store/api/DTO/Warehouses/Products/WarehouseDetails';

/**
 * Интерфейс ответа API с данными о складе
 */
export interface WarehouseProductCollection {
    count: number,
    data: WarehouseDetails;
}
