import { WarehouseProduct } from '@/store/api/DTO/Warehouses/Products/WarehouseProduct';
import { LaravelDateTime } from '@/store/api/DTO/LaravelDateTime';

/**
 * Основной интерфейс с информацией о складе
 */
export interface WarehouseDetails {
    id: number;
    name: string;
    products: WarehouseProduct[];
    created_at: LaravelDateTime;
    updated_at: LaravelDateTime;
}
