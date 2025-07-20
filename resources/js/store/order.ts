import { defineStore } from 'pinia';
import { V1 } from '@/store/api';
import { Order, OrderFilter } from '@/store/api/DTO/Orders/Order';
import { OrderCollection } from '@/store/api/DTO/Orders/OrderCollection';
import { CreateOrderTransfer } from '@/store/api/DTO/Orders/Transfer/CreateOrderTransfer';
import { UpdateOrderTransfer } from '@/store/api/DTO/Orders/Transfer/UpdateOrderTransfer';

export const useOrderStore = defineStore('order', {
    actions: {
        // Получить список заказов
        async fetchOrders(filter: OrderFilter = OrderFilter.CREATED_AT_DESC, offset = 0, limit = 10): Promise<OrderCollection> {
            return await V1.orders.index(filter, offset, limit);
        },

        // Создать новый заказ
        async createOrder(orderData: CreateOrderTransfer): Promise<Order> {
            return await V1.orders.store(orderData);
        },

        // Обновить заказ
        async updateOrder(order: Order, newData: UpdateOrderTransfer): Promise<Order> {
            return await V1.orders.update(order, newData);
        },

        // Отменить заказ
        async cancelOrder(order: Order): Promise<Order> {
            return await V1.orders.cancel(order);
        },

        // Завершить заказ
        async completeOrder(order: Order): Promise<Order> {
            return await V1.orders.complete(order);
        },

        // Восстановить заказ
        async restoreOrder(order: Order): Promise<Order> {
            return await V1.orders.restore(order);
        },
    },
});
