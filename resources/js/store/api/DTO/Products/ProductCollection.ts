import { FullProduct } from '@/store/api/DTO/Products/FullProduct';

/**
 * Основной интерфейс ответа API с товарами
 */
export interface ProductCollection {
    data: FullProduct[];
}
