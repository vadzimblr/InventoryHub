<?php

namespace App\DTOs\Response;

use App\Models\Supplier;
use Spatie\LaravelData\Data;
use Carbon\Carbon;

class SupplierResponseDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $address,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    )
    {
    }

    public static function fromModel(Supplier $supplier): self{
        return new self(
            $supplier->id,
            $supplier->name,
            $supplier->address,
            $supplier->created_at ? $supplier->created_at->format('d F Y H:i') : '',
            $supplier->updated_at ? $supplier->updated_at->format('d F Y H:i') : ''
        );
    }

    public static function collectionFromModels($suppliers): array
    {
        return $suppliers->map(static fn(Supplier $supplier) => self::fromModel($supplier))->toArray();
    }
}


