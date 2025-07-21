/**
 * Описывает товар на складе с информацией о количестве
 */
export interface WarehouseProduct {
    id: number;
    name: string;
    price: number;
    stock: number;
}
