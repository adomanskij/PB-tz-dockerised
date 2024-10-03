<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Controllers\AuthController;

class AuthMiddleware
{
    private AuthController $authController;

    public function __construct(AuthController $authController)
    {
        $this->authController = $authController;
    }

    public function handle(): void
    {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            if (!$this->authController->validateToken($token)) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
    }
}