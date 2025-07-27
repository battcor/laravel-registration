<?php

namespace App\Actions;

use App\Http\Requests\CheckEmailRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckEmailAction
{
    /**
     * Handle the email check action.
     *
     * @param string $email
     * @return JsonResponse
     */
    public function handle(CheckEmailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $isTaken = User::where('email', $data['email'])->exists();

            if ($isTaken) {
                return response()->json([
                    'available' => false,
                    'message' => 'This email is already taken.'
                ], 409);
            }

            return response()->json([
                'available' => true,
                'message' => 'Email is available.'
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
