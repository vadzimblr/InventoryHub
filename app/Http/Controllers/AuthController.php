<?php

namespace App\Http\Controllers;

use App\DTOs\UserLoginDto;
use App\DTOs\UserRegistrationDto;
use App\Exceptions\AuthenticationFailedException;
use App\Models\User;
use App\Services\AuthenticationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthenticationServiceInterface $authenticationService
    ){}
    public function login(Request $request){
        $userLoginDto = new UserLoginDto(
            $request->get('email'),
            $request->get('password'),
        );

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try{
            $token = $this->authenticationService->loginUser($userLoginDto);
            return response()->json([
                'token' => $token,
                'redirect' => '/products'
            ], 200);
        }catch (ValidationException $e){
            return back()->withErrors(['message' => $e->getMessage()]);
        }

    }
    public function register(Request $request){
        try {
            $userRegistrationDto = new UserRegistrationDto(
                $request->name,
                $request->email,
                $request->password,
                $request->password_confirmation,
                'client'
            );

            $this->authenticationService->registerUser($userRegistrationDto);

            return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
        } catch (AuthenticationFailedException $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }

    }
}
