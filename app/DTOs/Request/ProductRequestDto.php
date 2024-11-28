<?php

namespace App\DTOs\Request;

use App\Models\Product;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class ProductRequestDto extends Data
{
    public function __construct(
        #[Rule('required|string|max:255')]
        private readonly string $name,
        #[Rule('required|numeric|min:0')]
        private readonly float $price,
        #[Rule('required|integer|exists:suppliers,id')]
        private readonly int $supplierId,
        #[Rule('required|integer|exists:users,id')]
        private readonly int $createdBy,
        #[Rule('required|numeric|min:0')]
        private readonly float $quantity,
        #[Rule('required|string|max:10')]
        private readonly string $unit,
        #[Rule('boolean')]
        private readonly bool $isWholeUnit = true,
        #[Rule('nullable|string|max:500')]
        private readonly ?string $description = null,

    ) {}
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            price: $data['price'],
            supplierId: $data['supplierId'],
            createdBy: $data['createdBy'],
            quantity: $data['quantity'],
            unit: $data['unit'],
            isWholeUnit: $data['isWholeUnit'] ?? true,
            description: $data['description'] ?? null,
        );
    }
    public function toModel(): Product
    {
        $product = new Product();
        $product->name = $this->name;
        $product->description = $this->description;
        $product->price = $this->price;
        $product->quantity = $this->quantity;
        $product->unit = $this->unit;
        $product->is_whole_unit = $this->isWholeUnit;
        $product->supplier_id = $this->supplierId;
        $product->created_by = $this->createdBy;
        return $product;
    }
}
