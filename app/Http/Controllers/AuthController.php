<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Services\UserService;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterUserRequest $request)
    {
        try {
            $user = $this->userService->register($request->validated());

            $message = $user->isVendor()
                ? 'Registration successful! Please wait for admin approval before logging in.'
                : 'User Created Successfully!';

            return response()->json([
                'message' => $message,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'role_id' => $user->role_id,
                    'is_approved' => $user->is_approved,
                ]
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $result = $this->userService->login($request->validated());
            $user = $result['user'];

            return response()->json([
                'message' => 'User Logged In Successfully!',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'role_id' => $user->role_id,
                    'is_approved' => $user->is_approved,
                    'store' => $user->isVendor() ? $user->store : null,
                ],
                'token' => $result['token'],
                'token_type' => 'Bearer'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function logout()
    {
        try {
            $this->userService->logout();

            return response()->json([
                'message' => 'Successfully logged out'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
