<?php

namespace App\DTOs\Request;

use App\Models\Invoice;
use Spatie\LaravelData\Data;

class InvoiceRequestDto extends Data
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $issuedBy,
    )
    {}
    public static function fromArray(array $data): self
    {
        return new self(
            orderId: $data['orderId'],
            issuedBy: $data['issuedBy'],
        );
    }
    public function toModel(): Invoice
    {
        return new Invoice([
            'order_id' => $this->orderId,
            'issued_by' => $this->issuedBy,
        ]);
    }
}
