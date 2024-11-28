<?php

namespace App\DTOs\Response\Client;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Data;

class OrderItemClientResponseDto extends Data
{
    public function __construct(
        public string $productName,
        public readonly float $quantity,
        public readonly float $totalAmount,
        public readonly string $unit
    )
    {}
    public static function fromModel(OrderItem $orderItem): self
    {
        return new self(
            productName: $orderItem->product->name ?? 'Unknown Product',
            quantity: $orderItem->quantity,
            totalAmount: $orderItem->total_amount,
            unit: $orderItem->unit
        );
    }
    public static function fromCollection(Collection $orderItems): array
    {
        return $orderItems->map(fn(OrderItem $orderItem) => self::fromModel($orderItem))->toArray();
    }
}