<?php

namespace App\DTOs\Request;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class ProductRequestDto extends Data
{
    public function __construct(
        private readonly string $name,
        private readonly float $price,
        private readonly int $supplierId,
        private readonly int $createdBy,
        private readonly float $quantity,
        private readonly string $unit,
        private readonly bool $isWholeUnit = true,
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
