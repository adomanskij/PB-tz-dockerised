<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AccountService;

class AccountController
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function create(array $data)
    {
        $accountNumber = $data['account_number'];
        $balance = (float)$data['balance'];
        $currency = $data['currency'];

        try {
            $newAccount = $this->accountService->createAccount($accountNumber, $balance, $currency);
            return "Account created: " . $newAccount->getAccountNumber();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getDetails(string $accountNumber)
    {
        $accountDetails = $this->accountService->getAccountDetails($accountNumber);
        if ($accountDetails === null) {
            return null;
        }
        return "Account Number: " . $accountDetails->getAccountNumber() . ", Balance: " . $accountDetails->getBalance() . ", Currency: " . $accountDetails->getCurrency();
    }
    
    public function delete(string $accountNumber)
    {
        $deleted = $this->accountService->deleteAccount($accountNumber);
        return $deleted ? "Account deleted successfully." : "Failed to delete account.";
    }
}