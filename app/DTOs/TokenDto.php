<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class TokenDto extends Data
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken
    ) {}
}
