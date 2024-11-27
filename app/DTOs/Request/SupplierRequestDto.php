<?php

namespace App\DTOs\Request;

use App\Models\Supplier;
use Spatie\LaravelData\Data;

class SupplierRequestDto extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $address,
    )
    {
    }
}
