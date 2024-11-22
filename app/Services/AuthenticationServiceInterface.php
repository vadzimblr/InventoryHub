<?php

namespace App\Services;

use App\DTOs\TokenDto;
use App\DTOs\UserLoginDto;
use App\DTOs\UserRegistrationDto;

interface AuthenticationServiceInterface
{
    public function registerUser(UserRegistrationDto $userRegistrationDto): void;
    public function loginUser(UserLoginDto $userLoginDto);
}
