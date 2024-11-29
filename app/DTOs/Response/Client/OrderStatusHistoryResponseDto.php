<?php

namespace App\DTOs\Response\Client;

use App\Models\OrderStatusesHistory;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\Data;

class OrderStatusHistoryResponseDto extends Data
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $changedBy,
        public readonly string $updatedAt
    ) {}


    public static function fromModel(OrderStatusesHistory $history): self
    {
        return new self(
            status: $history->getRelation('status')->name ?? 'Unknown',
            changedBy: $history->changedBy->email ?? 'System',
            updatedAt: $history->updated_at->toDateTimeString()
        );
    }

    public static function fromCollection($histories): array
    {
        return $histories->map(fn(OrderStatusesHistory $history) => self::fromModel($history))->toArray();
    }
}
