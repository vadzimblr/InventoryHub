<?php

namespace App\DTOs\Response;

use App\Models\Waybill;
use Spatie\LaravelData\Data;

class WaybillResponseDto extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $invoiceId,
        public readonly string $storekeeper,
        public readonly string $handoffTime,
    )
    {
    }
    public static function fromModel(Waybill $waybill): self
    {
        return new self(
            id: $waybill->id,
            invoiceId: $waybill->invoice_id,
            storekeeper: $waybill->storekeeper->name,
            handoffTime: $waybill->handoff_time->toDateTimeString(),
        );
    }
    public static function fromModelCollection($waybills): array
    {
        return $waybills->map(fn (Waybill $waybill) => self::fromModel($waybill))->toArray();
    }
}
