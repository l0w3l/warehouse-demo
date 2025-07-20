export interface OrderProductItem {
    id: number;       // required|integer|exists:products,id
    quantity: number; // required|integer|min:1
}
