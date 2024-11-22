<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class UserRegistrationDto extends Data
{
    public function __construct(
      public readonly string $name,
      public readonly string $email,
      public readonly string $password,
      public readonly string $passwordConfirmation,
      public readonly string $role
    ){}
}
