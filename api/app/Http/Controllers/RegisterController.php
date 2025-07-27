<?php

namespace App\Http\Controllers;

use App\Actions\CheckEmailAction;
use App\Actions\RegisterAction;
use App\Http\Requests\CheckEmailRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * Register a new user.
     * 
     * @param RegisterRequest $request
     * @param RegisterAction $action
     * @return JsonResponse
     */
    public function register(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        $user = $action->handle($request);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    /**
     * Check if the email is available for registration.
     *
     * @param CheckEmailRequest $request
     * @param CheckEmailAction $action
     * @return JsonResponse
     */
    public function checkEmail(CheckEmailRequest $request, CheckEmailAction $action): JsonResponse
    {
        return $action->handle($request);
    }
}
