<?php

namespace App\Services;

use App\DTOs\Response\UserDto;
use App\DTOs\TokenDto;
use App\DTOs\UserLoginDto;
use App\DTOs\UserRegistrationDto;

interface AuthenticationServiceInterface
{
    public function registerUser(UserRegistrationDto $userRegistrationDto): UserDto;
    public function loginUser(UserLoginDto $userLoginDto): string;
}
