<?php

namespace App\Http\Controllers;

use App\DTOs\UserRegistrationDto;
use App\Services\AdminService\Api\AdminServiceInterface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    public function __construct(
        private readonly AdminServiceInterface $adminService
    ){}
    public function showAllUsers(Request $request): JsonResponse
    {

        return response()->json($this->adminService->getAllUsers());
    }

    public function showAllRoles(Request $request): JsonResponse
    {
        return response()->json($this->adminService->getAllRoles());
    }
    public function addUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'role' => 'nullable|string',
        ]);

        $userDto = new UserRegistrationDto(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
            passwordConfirmation: $validated['password_confirmation'],
            role: $validated['role'] ?? null
        );

        return response()->json(["user"=>$this->adminService->storeUser($userDto)], 201);
    }

}
