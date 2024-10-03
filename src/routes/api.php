<?php

// Обробка запиту для отримання деталей акаунту
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['account_number']) && !isset($_GET['action'])) {
    $accountNumber = $_GET['account_number'];
    $accountDetails = $accountController->getDetails($accountNumber);
    if ($accountDetails !== null) {
        echo $accountDetails;
    } else {
        echo "Account not found.";
    }
    exit;
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
    exit;
}

// Обробка запиту на отримання історії транзакцій
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'transaction_history' && isset($_GET['account_number'])) {
    $accountNumber = $_GET['account_number'];
    $transactionHistory = $transactionController->history($accountNumber);
    echo $transactionHistory;
    exit;
}

// Обробка запиту на видалення акаунту
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['account_number'])) {
    $accountNumber = $_GET['account_number'];
    $deleteResponse = $accountController->delete($accountNumber);
    echo $deleteResponse;
    exit;
}