<?php

namespace App\DTOs\Response\Client;


use App\Models\Order;
use Spatie\LaravelData\Data;

class OrderClientResponseDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly float $totalAmount,
        public readonly string $status,
        public readonly string $createdAt,
        public readonly array $orderItems,
    ){}
    public static function fromModel(Order $order): self
    {
        return new self(
            id: $order->id,
            totalAmount: $order->total_amount,
            status: $order->currentStatus->name ?? 'Unknown Status',
            createdAt: $order->created_at->format('d.m.Y H:i'),
            orderItems: OrderItemClientResponseDto::fromCollection($order->orderItems)
        );
    }
}
