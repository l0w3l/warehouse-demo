import { defineStore } from 'pinia';
import { V1 } from '@/store/api';
import { Order, OrderSort, OrderStatus } from '@/store/api/DTO/Orders/Order';
import { OrderCollection } from '@/store/api/DTO/Orders/OrderCollection';
import { CreateOrderTransfer } from '@/store/api/DTO/Orders/Transfer/CreateOrderTransfer';
import { UpdateOrderTransfer } from '@/store/api/DTO/Orders/Transfer/UpdateOrderTransfer';

export const useOrderStore = defineStore('order', {
    actions: {
        // Получить список заказов
        async fetchOrders(offset = 0, limit = 10, sort: OrderSort = OrderSort.CREATED_AT_DESC, filter: OrderStatus): Promise<OrderCollection> {
            const orders: OrderCollection = await V1.orders.index(offset, limit, sort, filter);

            orders.data.forEach((order: Order) => order.total_amount = Math.ceil(order.total_amount * 100) / 100);

            return orders;
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
