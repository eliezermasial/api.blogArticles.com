<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    
    public function register(Request $request): JsonResponse
    {
        return $this->authService->register($request);
    }

    public function login(Request $request): JsonResponse {
        return $this->authService->login($request);
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->authService->logout($request);
    }
}
