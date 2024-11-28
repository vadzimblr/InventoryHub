<?php

namespace App\DTOs\Request;

use Spatie\LaravelData\Data;

class OrderItemRequestDto extends Data
{
    public function __construct(
        public readonly float $quantity,
        public readonly int $productId,
    )
    {}
    public static function fromArray(array $data): self
    {
        return new self(
            quantity: $data['quantity'],
            productId: $data['productId'],
        );
    }
}
