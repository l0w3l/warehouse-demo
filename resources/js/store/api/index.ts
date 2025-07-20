import httpClient from '@/store/api/client';
import { WarehouseCollection } from '@/store/api/DTO/Warehouses/WarehouseCollection';
import { WarehouseItem } from '@/store/api/DTO/Warehouses/WarehouseItem';
import { StockChangeCollection } from '@/store/api/DTO/StockHistory/StockChangeCollection';
import { WarehouseProductCollection } from '@/store/api/DTO/Warehouses/Products/WarehouseProductCollection';
import { ProductCollection } from '@/store/api/DTO/Products/ProductCollection';
import { OrderCollection } from '@/store/api/DTO/Orders/OrderCollection';
import { Order, OrderFilter } from '@/store/api/DTO/Orders/Order';
import { CreateOrderTransfer } from '@/store/api/DTO/Orders/Transfer/CreateOrderTransfer';
import { UpdateOrderTransfer } from '@/store/api/DTO/Orders/Transfer/UpdateOrderTransfer';

export const V1 = {
    warehouses: {
        index: async (offset: number = 0, limit: number = 10): Promise<WarehouseCollection> =>
            (await httpClient.get<WarehouseCollection>(`/v1/warehouses`, { params: { offset, limit } })).data,

        history: async (offset: number = 0, limit: number = 10): Promise<StockChangeCollection> =>
            (await httpClient.get<StockChangeCollection>(`/v1/warehouse/history`, { params: { offset, limit } })).data,

        products: {
            index: async (warehouse: WarehouseItem, offset: number = 0, limit: number = 10): Promise<WarehouseProductCollection> =>
                (await httpClient.get<WarehouseProductCollection>(`/v1/warehouse/${warehouse.id}/products`, { params: { offset, limit } })).data,
        },
    },
    products: {
        index: async (offset: number = 0, limit: number = 10): Promise<ProductCollection> =>
            (await httpClient.get<ProductCollection>(`/v1/products`, { params: { offset, limit } })).data,
    },
    orders: {
        index: async (filter: OrderFilter = OrderFilter.CREATED_AT_DESC, offset: number = 0, limit: number = 10): Promise<OrderCollection> =>
            (await httpClient.get<OrderCollection>(`/v1/orders`, { params: { filter: filter.valueOf(), offset, limit } })).data,

        store: async (order: CreateOrderTransfer): Promise<Order> => (await httpClient.post<Order>(`/v1/orders`, order)).data,

        update: async (order: Order, newOrder: UpdateOrderTransfer): Promise<Order> =>
            (await httpClient.put<Order>(`/v1/orders/${order.id}`, newOrder)).data,

        cancel: async (order: Order): Promise<Order> => (await httpClient.get(`/v1/orders/${order.id}/cancel`)).data,

        complete: async (order: Order): Promise<Order> => (await httpClient.get(`/v1/orders/${order.id}/complete`)).data,

        restore: async (order: Order): Promise<Order> => (await httpClient.get(`/v1/orders/${order.id}/restore`)).data,
    },
};
