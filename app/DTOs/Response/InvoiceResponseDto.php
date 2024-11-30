<?php

namespace App\DTOs\Response;

use App\Models\Invoice;
use Spatie\LaravelData\Data;

class InvoiceResponseDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $orderId,
        public readonly ?string $paidAt,
        public readonly string $createdAt,
        public readonly string $issuedBy
    )
    {
    }
    public static function fromModel(Invoice $invoice): self
    {
        return new self(
            id: $invoice->id,
            orderId: $invoice->order_id,
            paidAt: ($invoice->paid_at === null) ? null : $invoice->paid_at,
            createdAt: $invoice->created_at->toDateTimeString(),
            issuedBy: $invoice->issuedBy->email,
        );
    }
    public static function fromModelCollection($invoices): array
    {
        return $invoices->map(fn(Invoice $invoice) => self::fromModel($invoice))->toArray();
    }
}
