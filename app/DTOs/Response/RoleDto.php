<?php

namespace App\DTOs\Response;

use Spatie\LaravelData\Data;
use Spatie\Permission\Models\Role;

class RoleDto extends Data
{
    public function __construct(
        public readonly string $name
    )
    {}

    public static function fromModel(Role $role): self
    {
        return new self($role->name);
    }
    public static function collectionFromModels($roles): array
    {
        return $roles->map(static fn (Role $role) => self::fromModel($role))->toArray();
    }
}
