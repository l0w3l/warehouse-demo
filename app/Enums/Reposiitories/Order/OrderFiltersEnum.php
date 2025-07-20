<?php

namespace App\Enums\Reposiitories\Order;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

enum OrderFiltersEnum: string
{
    case COMPLETED_AT_DESC = 'completed_at_desc';
    case COMPLETED_AT_ASC = 'completed_at_asc';
    case CREATED_AT_DESC = 'created_at_desc';
    case CREATED_AT_ASC = 'created_at_asc';
    case UPDATED_AT_DESC = 'updated_at_desc';
    case UPDATED_AT_ASC = 'updated_at_asc';
    case TOTAL_PRICE_DESC = 'total_amount_desc';
    case TOTAL_AMOUNT_ASC = 'total_amount_asc';
    case TOTAL_QUANTITY_DESC = 'total_quantity_desc';
    case TOTAL_QUANTITY_ASC = 'total_quantity_asc';

    /**
     * @param  Builder<Order>  $query
     * @return Builder<Order>
     */
    public function resolve(Builder $query): Builder
    {
        return match ($this) {
            self::COMPLETED_AT_DESC => $query->orderBy('completed_at', 'desc'),
            self::COMPLETED_AT_ASC => $query->orderBy('completed_at', 'asc'),
            self::CREATED_AT_DESC => $query->orderBy('created_at', 'desc'),
            self::CREATED_AT_ASC => $query->orderBy('created_at', 'asc'),
            self::UPDATED_AT_DESC => $query->orderBy('updated_at', 'desc'),
            self::UPDATED_AT_ASC => $query->orderBy('updated_at', 'asc'),

            self::TOTAL_PRICE_DESC => $query->select('orders.*')->selectSub(function (QueryBuilder $query) {
                $query->selectRaw('sum(price) as price')
                    ->from('order_items')
                    ->join('products', 'products.id', '=', 'order_items.product_id')
                    ->whereColumn('order_items.order_id', 'orders.id');
            }, 'total_amount')->orderBy('total_amount', 'desc'),

            self::TOTAL_AMOUNT_ASC => $query->select('orders.*')->selectSub(function (QueryBuilder $query) {
                $query->selectRaw('sum(price) as price')
                    ->from('order_items')
                    ->join('products', 'products.id', '=', 'order_items.product_id')
                    ->whereColumn('order_items.order_id', 'orders.id');
            }, 'total_amount')->orderBy('total_amount', 'asc'),

            self::TOTAL_QUANTITY_DESC => $query->select('orders.*')->selectSub(function (QueryBuilder $query) {
                $query->selectRaw('count(*) as total_quantity')
                    ->from('order_items')
                    ->join('products', 'products.id', '=', 'order_items.product_id')
                    ->whereColumn('order_items.order_id', 'orders.id');
            }, 'total_quantity')->orderBy('total_quantity', 'desc'),

            self::TOTAL_QUANTITY_ASC => $query->select('orders.*')->selectSub(function (QueryBuilder $query) {
                $query->selectRaw('count(*) as total_quantity')
                    ->from('order_items')
                    ->join('products', 'products.id', '=', 'order_items.product_id')
                    ->whereColumn('order_items.order_id', 'orders.id');
            }, 'total_quantity')->orderBy('total_quantity', 'asc'),
        };
    }
}
