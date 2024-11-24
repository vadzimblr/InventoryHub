<?php

namespace App\Services;

use App\DTOs\UserLoginDto;
use App\DTOs\UserRegistrationDto;
use App\Exceptions\AuthenticationFailedException;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * @throws AuthenticationFailedException
     */
    public function registerUser(UserRegistrationDto $userRegistrationDto): void
    {
        if($userRegistrationDto->password !== $userRegistrationDto->passwordConfirmation){
            throw new AuthenticationFailedException('The password and its confirmation do not match.');
        }

        if (User::where('email', $userRegistrationDto->email)->exists()) {
            throw new AuthenticationFailedException('The email address is already taken.');
        }

        $user = new User();
        $user->name = $userRegistrationDto->name;
        $user->email = $userRegistrationDto->email;
        $user->password = Hash::make($userRegistrationDto->password);
        $user->email_verified_at = Carbon::now();
        $user->save();

        $user->assignRole('client');
    }

    /**
     * @throws AuthenticationFailedException
     * @throws ValidationException
     */
    public function loginUser(UserLoginDto $userLoginDto): string
    {
        $user = User::where('email', $userLoginDto->email)->first();
        if (!$user || !Hash::check($userLoginDto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        return $user->createToken('API Token')->plainTextToken;
    }
}
