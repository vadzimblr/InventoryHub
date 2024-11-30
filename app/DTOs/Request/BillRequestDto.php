<?php

namespace App\DTOs\Request;

use App\Models\Bill;
use Spatie\LaravelData\Data;

class BillRequestDto extends Data
{
    public function __construct(
        public readonly int $supplierOrderId,
        public readonly int $accountantId
    )
    {}
    public static function fromArray(array $data): self
    {
        return new self(
            supplierOrderId: $data['supplierOrderId'],
            accountantId: $data['accountantId']
        );
    }
    public function toModel(): Bill
    {
        return new Bill([
            'supplier_order_id' => $this->supplierOrderId,
            'accountant_id' => $this->accountantId,
        ]);
    }
}
