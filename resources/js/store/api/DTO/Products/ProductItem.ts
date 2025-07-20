/**
 * Описывает товар
 */
export interface ProductItem {
    id: number;
    name: string;
    price: number;
    created_at: string; // ISO format
    updated_at: string; // ISO format
}
