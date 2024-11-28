<?php

namespace App\DTOs\Response;

use App\Models\Product;
use Spatie\LaravelData\Data;

class ProductResponseDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
        public readonly float $quantity,
        public readonly string $unit,
        public readonly bool $isWholeUnit,
        public readonly string $supplierName,
        public readonly string $creatorEmail,
        public readonly string $createdAt
    ) {}

    /**
     * Создание DTO из модели
     *
     * @param Product $product
     * @return self
     */
    public static function fromModel(Product $product): self
    {
        return new self(
            id: $product->id,
            name: $product->name,
            description: $product->description,
            price: $product->price,
            quantity: $product->quantity,
            unit: $product->unit,
            isWholeUnit: $product->is_whole_unit,
            supplierName: $product->supplier->name ?? 'Unknown Supplier',
            creatorEmail: $product->creatorId->email ?? 'Unknown Creator',
            createdAt: $product->created_at->toDateTimeString()
        );
    }
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            description: $data['description'] ?? null,
            price: $data['price'],
            quantity: $data['quantity'],
            unit: $data['unit'],
            isWholeUnit: $data['isWholeUnit'],
            supplierName: $data['supplier_name'] ?? 'Unknown Supplier',
            creatorEmail: $data['creator_email'] ?? 'Unknown Creator',
            createdAt: $data['created_at'] ?? now()->toDateTimeString()
        );
    }

    public static function fromCollection($products): array
    {
        return $products->map(fn(Product $product) => self::fromModel($product))->toArray();
    }
}
