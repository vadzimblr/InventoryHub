<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class UserLoginDto extends Data
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ){}
}
