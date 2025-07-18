<?php

namespace App\Enums\Models\Order;

enum OrderStatusEnum: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    /**
     * @return string[]
     */
    public static function toArray(): array
    {
        return array_map(fn (OrderStatusEnum $enum) => $enum->value, OrderStatusEnum::cases());
    }
}
