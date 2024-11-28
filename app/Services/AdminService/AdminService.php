<?php

namespace App\Services\AdminService;

use App\DTOs\Response\RoleDto;
use App\DTOs\Response\UserDto;
use App\DTOs\UserRegistrationDto;
use App\Models\User;
use App\Services\AdminService\Api\AdminServiceInterface;
use App\Services\AuthenticationServiceInterface;
use Spatie\Permission\Models\Role;

readonly class AdminService implements Api\AdminServiceInterface
{
    public function __construct(
        private AuthenticationServiceInterface $authService,
    ){
    }
    public function getAllUsers(): array
    {
        $users = User::all();
        return UserDto::collectionFromModels($users);
    }

    public function getAllRoles(): array
    {
        $roles = Role::all()->reject(function ($role) {
            return $role->name === 'admin' || $role->name === 'client';
        });
        $roleDtos = RoleDto::collectionFromModels($roles);
        return array_map(function ($roleDto) {
            return trim($roleDto['name']);
        }, $roleDtos);
    }

    public function storeUser(UserRegistrationDto $userRegistrationDto): UserDto
    {
        return $this->authService->registerUser($userRegistrationDto);
    }
}
