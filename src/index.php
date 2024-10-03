<?php

declare(strict_types=1);

require_once __DIR__ . '/database/connection.php';
require_once __DIR__ . '/app/Controllers/AccountController.php';
require_once __DIR__ . '/app/Controllers/TransactionController.php';
require_once __DIR__ . '/app/Repositories/AccountRepository.php';
require_once __DIR__ . '/app/Repositories/TransactionRepository.php';
require_once __DIR__ . '/app/Services/AccountService.php';
require_once __DIR__ . '/app/Services/TransactionService.php';
require_once __DIR__ . '/app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/app/Controllers/AuthController.php'; // Додано підключення до AuthController

use App\Repositories\AccountRepository;
use App\Services\AccountService;
use App\Controllers\AccountController;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
use App\Controllers\TransactionController;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Services\AuthService;

// Підключення до бази даних
$pdo = require __DIR__ . '/database/connection.php';

// Ініціалізація репозиторіїв, сервісів і контролерів
$accountRepository = new AccountRepository($pdo);
$accountService = new AccountService($accountRepository);
$accountController = new AccountController($accountService);

$transactionRepository = new TransactionRepository($pdo);
$transactionService = new TransactionService($transactionRepository);
$transactionController = new TransactionController($transactionService);

// Ініціалізація контролера аутентифікації та мідлвару для аутентифікації
$authController = new AuthController(new AuthService()); // Створіть екземпляр AuthController
$authMiddleware = new AuthMiddleware($authController); // Передайте його в AuthMiddleware

// Обробка маршруту для публічних запитів
require_once __DIR__ . '/routes/public.php';

// Виклик мідлвару перед обробкою захищених запитів
$authMiddleware->handle(); 

// Обробка маршруту для захищених запитів
require_once __DIR__ . '/routes/api.php';