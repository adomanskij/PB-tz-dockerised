<?php

// Обробка запиту на створення акаунту
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_account') {
    echo '1';
    $data = [
        'account_number' => $_POST['account_number'],
        'balance' => $_POST['balance'],
        'currency' => $_POST['currency'],
    ];
    $response = $accountController->create($data);
    echo $response;
    exit ;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'get_token') {
    // Логіка генерації токена
    $token = $authController->generateToken();

    // Відправка токена у відповідь
    echo json_encode(['token' => $token]);
    exit;
}

// Додаткові публічні маршрути...