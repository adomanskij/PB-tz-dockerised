<?php

declare(strict_types=1);

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

class AuthService
{
    private string $key;

    public function __construct()
    {
        // Завантаження змінних середовища з .env файлу (якщо потрібно)
        if (file_exists(__DIR__ . '/../../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        // Отримання секретного ключа
        $this->key = $_ENV['JWT_SECRET_KEY'];

        // Перевірка наявності ключа
        if ($this->key === false) {
            throw new \Exception('JWT_SECRET_KEY не знайдено в змінних середовища.');
        }
    }

    public function generateToken(): string
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + (60 * 60), // Термін дії токена 1 година
            'sub' => 'user'
        ];
        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function validateToken(string $token): ?string
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            return $decoded->sub; // Повертає 'sub' з декодованого токена
        } catch (\Exception $e) {
            return null; // Якщо токен недійсний, повертає null
        }
    }
}