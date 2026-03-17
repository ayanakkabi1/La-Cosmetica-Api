<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\DTO\RegisterDTO;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $dto = new RegisterDTO($request->validated());

        $user = $this->authService->register($dto);

        return response()->json([
            'message' => 'User created',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $token = $this->authService->login(
            $request->only('email', 'password')
        );

        if (!$token) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }
    public function logout(Request $request)
    {
        $this->authService->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}