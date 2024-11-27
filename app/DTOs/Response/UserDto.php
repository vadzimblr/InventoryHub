<?php

namespace App\DTOs\Response;

use App\Models\User;
use Spatie\LaravelData\Data;

class UserDto extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $role
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            role: $user->getRoleNames()->first() ?? 'No role assigned'
        );
    }

    public static function collectionFromModels($users): array
    {
        return $users->map(static fn(User $user) => self::fromModel($user))->toArray();
    }
}
