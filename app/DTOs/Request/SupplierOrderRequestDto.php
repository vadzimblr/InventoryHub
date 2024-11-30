<?php

namespace App\DTOs\Request;

use App\Models\SupplierOrder;
use Spatie\LaravelData\Data;

class SupplierOrderRequestDto extends Data
{
    public function __construct(
        public readonly int $supplierId,
        public readonly int $productId,
        public readonly int $managerId,
        public readonly int $quantity,
    )
    {}
    public static function fromArray(array $data): self
    {
        return new self(
            supplierId: $data['supplierId'],
            productId: $data['productId'],
            managerId: $data['managerId'],
            quantity: $data['quantity'],
        );
    }
    public function toModel(): SupplierOrder{
        return new SupplierOrder([
           'supplier_id'=>$this->supplierId,
           'product_id'=>$this->productId,
           'manager_id'=>$this->managerId,
           'quantity'=>$this->quantity,
        ]);
    }
}
