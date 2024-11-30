<?php

namespace App\DTOs\Response;

use App\Models\Bill;

class BillResponseDto
{
    public function __construct(
        public int $id,
        public int $supplierOrderId,
        public ?string $paidAt,
        public string $accountant,
        public string $createdAt,
    ){}
    public static function fromModel(Bill $bill): self
    {
        return new self(
            id: $bill->id,
            supplierOrderId: $bill->supplier_order_id,
            paidAt: $bill->paid_at,
            accountant: $bill->accountant->email,
            createdAt: $bill->created_at->toDateTimeString()
        );
    }
    public static function fromCollectionModels($bills): array
    {
        return $bills->map(function ($bill) {
            return self::fromModel($bill);
        })->toArray();
    }
}
