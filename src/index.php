<?php

declare(strict_types=1);

require_once __DIR__ . '/database/connection.php';
require_once __DIR__ . '/app/Controllers/AccountController.php';
require_once __DIR__ . '/app/Controllers/TransactionController.php';
require_once __DIR__ . '/app/Repositories/AccountRepository.php';
require_once __DIR__ . '/app/Repositories/TransactionRepository.php';
require_once __DIR__ . '/app/Services/AccountService.php';
require_once __DIR__ . '/app/Services/TransactionService.php';

use App\Repositories\AccountRepository;
use App\Services\AccountService;
use App\Controllers\AccountController;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
use App\Controllers\TransactionController;

$pdo = require __DIR__ . '/database/connection.php';
$accountRepository = new AccountRepository($pdo);
$accountService = new AccountService($accountRepository);
$accountController = new AccountController($accountService);

$transactionRepository = new TransactionRepository($pdo);
$transactionService = new TransactionService($transactionRepository);
$transactionController = new TransactionController($transactionService);

// Обробка запиту на створення акаунту
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_account') {
    $data = [
        'account_number' => $_POST['account_number'],
        'balance' => $_POST['balance'],
        'currency' => $_POST['currency'],
    ];
    $response = $accountController->create($data);
    echo $response;
}

// Обробка запиту для отримання деталей акаунту
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['account_number']) && !isset($_GET['action'])) {
    $accountNumber = $_GET['account_number'];
    $accountDetails = $accountController->getDetails($accountNumber);
    if ($accountDetails !== null) {
        echo $accountDetails;
    } else {
        echo "Account not found.";
    }
}

// Обробка запиту на переведення коштів
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'transfer') {
    $data = [
        'account_from' => $_POST['account_from'],
        'account_to' => $_POST['account_to'],
        'amount' => $_POST['amount'],
    ];
    $response = $transactionController->transfer($data);
    echo $response;
}

// Обробка запиту на отримання історії транзакцій
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'transaction_history' && isset($_GET['account_number'])) {
    $accountNumber = $_GET['account_number'];
    $transactionHistory = $transactionController->history($accountNumber);
    echo $transactionHistory;
}

// Обробка запиту на видалення аккаунту
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['account_number'])) {
    $accountNumber = $_GET['account_number'];
    $deleteResponse = $accountController->delete($accountNumber);
    echo $deleteResponse;
}