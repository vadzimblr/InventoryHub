<?php

namespace App\DTOs\Request;

use Spatie\LaravelData\Data;

class OrderRequestDto extends Data
{
    public function __construct(
        public readonly string $address,
        public readonly int $clientId,
        public array $orderItems,
    ){}
    public static function fromArray(array $data): self
    {
        return new self(
            address: $data['address'],
            clientId: $data['clientId'],
            orderItems: array_map(
                fn(array $item) => OrderItemRequestDto::fromArray($item),
                $data['orderItems'] ?? []
            )
        );
    }
}
