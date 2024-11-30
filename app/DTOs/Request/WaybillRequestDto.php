<?php

namespace App\DTOs\Request;

use App\Models\Waybill;
use Spatie\LaravelData\Data;

class WaybillRequestDto extends Data
{
    public function __construct(
        public readonly int $invoiceId,
        public readonly int $storekeeperId,
    )
    {}
    public static function fromArray(array $data): self
    {
        return new self(
            $data['invoiceId'],
            $data['storekeeperId'],
        );
    }
    public function toModel(): Waybill
    {
        return new Waybill([
            'invoice_id' => $this->invoiceId,
            'storekeeper_id' => $this->storekeeperId,
        ]);
    }
}
