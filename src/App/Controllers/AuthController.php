<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function generateToken(): string
    {
        // Генерація токена (можна додати додаткову логіку)
        return $this->authService->generateToken();
    }

    public function validateToken(string $token): bool
    {
        return $this->authService->validateToken($token) !== null;
    }
}