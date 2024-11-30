<?php

namespace App\DTOs\Response;

use App\Models\Order;
use Spatie\LaravelData\Data;

class OrderResponseDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly float $totalAmount,
        public readonly string $status,
        public readonly string $address,
        public readonly string $createdAt,
        public readonly array $orderItems,
    ){}
    public static function fromModel(Order $order): self
    {
        return new self(
            id: $order->id,
            totalAmount: $order->total_amount,
            status: $order->currentStatus->name ?? 'Unknown Status',
            address: $order->address,
            createdAt: $order->created_at->format('d.m.Y H:i'),
            orderItems: OrderItemResponseDto::fromCollection($order->orderItems)
        );
    }
    public static function fromCollection($orders): array
    {
        return $orders->map(fn(Order $order) => self::fromModel($order))->toArray();
    }
}
