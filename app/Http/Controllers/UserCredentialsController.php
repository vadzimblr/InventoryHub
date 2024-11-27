<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCredentialsController extends Controller
{
    public function getUserRole(Request $request): JsonResponse
    {
        $user = auth()->user();

        if ($user) {

            $roles = $user->getRoleNames()->first();
            return response()->json([
                'role' => $roles,
            ]);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }
}
