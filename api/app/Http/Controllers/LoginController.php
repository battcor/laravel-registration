<?php

namespace App\Http\Controllers;

use App\Actions\LoginAction;
use App\Actions\LogoutAction;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Handle the login request.
     *
     * @param Request $request
     * @param LoginAction $action
     * @return JsonResponse
     */
    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        return $action->handle($request);
    }

    /**
     * Handle the logout request.
     *
     * @param Request $request
     * @param LogoutAction $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request, LogoutAction $action): JsonResponse
    {
        return $action->handle($request);
    }
}
