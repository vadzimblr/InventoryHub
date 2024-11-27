<?php

namespace App\Services\AdminService\Api;

use App\DTOs\Response\UserDto;
use App\DTOs\UserRegistrationDto;

interface AdminServiceInterface
{
    public function getAllUsers(): array;
    public function getAllRoles(): array;
    public function addUser(UserRegistrationDto $userRegistrationDto): UserDto;
}
