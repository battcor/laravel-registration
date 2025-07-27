<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterAction
{
    /**
     * Handle the registration action.
     *
     * @param Request $request
     * @return User
     */
    public function handle(Request $request): User
    {
        try {
            $data = $request->validated();

            return User::create([
                'username' => $data['username'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'mobile_number' => $data['mobile_number'],
                'password' => $data['password']
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
