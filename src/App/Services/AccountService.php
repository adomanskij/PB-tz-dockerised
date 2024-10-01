<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AccountRepository;

class AccountService
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function createAccount(string $accountNumber, float $balance, string $currency)
    {
        return $this->accountRepository->create($accountNumber, $balance, $currency);
    }

    public function getAccountDetails(string $accountNumber)
    {
        return $this->accountRepository->findByAccountNumber($accountNumber);
    }

    public function deleteAccount(string $accountNumber): bool
    {
        return $this->accountRepository->delete($accountNumber);
    }
}