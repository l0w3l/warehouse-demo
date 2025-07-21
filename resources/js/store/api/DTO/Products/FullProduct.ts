import { WarehouseStock } from '@/store/api/DTO/Products/WarehouseStock';

/**
 * Описывает товар с информацией о его наличии на складах
 */
export interface FullProduct {
    id: number;
    name: string;
    price: number;
    warehouses: WarehouseStock[];
}
