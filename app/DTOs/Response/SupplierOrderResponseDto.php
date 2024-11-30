<?php

namespace App\DTOs\Response;

use App\Models\SupplierOrder;
use Spatie\LaravelData\Data;

class SupplierOrderResponseDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $supplier,
        public readonly string $product,
        public readonly float $quantity,
        public readonly float $total_amount,
        public readonly string $status,
        public readonly string $created_at,
    )
    {}
    public static function fromModel(SupplierOrder $order): self
    {
        return new self(
            id: $order->id,
            supplier: $order->supplier->name,
            product: $order->product->name,
            quantity: $order->quantity,
            total_amount: $order->total_amount,
            status: $order->orderStatus->name,
            created_at: $order->created_at->toDateTimeString()
        );
    }
    public static function fromModelCollection($orders): array
    {
        return $orders->map(fn(SupplierOrder $order) => self::fromModel($order))->toArray();
    }
}
