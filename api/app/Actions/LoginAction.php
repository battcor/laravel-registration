<?php

namespace App\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginAction
{
    /**
     * Handle the login action.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        $data = $request->only('username', 'password');

        if (!auth()->attempt($data)) {
            return response()->json([
                'message' => 'Invalid credentials. Please check your username and password.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Authentication successful, get the authenticated user
        $user = auth()->user();

        // Revoke all existing tokens for the user
        $user->tokens()->delete();

        // Generate a new API token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->only([
                'id',
                'name',
                'surname',
                'email'
            ]),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], Response::HTTP_OK);
    }
}
