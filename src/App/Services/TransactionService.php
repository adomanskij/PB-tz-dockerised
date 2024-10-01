<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function createTransaction(string $accountFrom, string $accountTo, float $amount)
    {
        return $this->transactionRepository->create($accountFrom, $accountTo, $amount);
    }

    public function getTransactionHistory(string $accountNumber)
    {
        return $this->transactionRepository->getHistory($accountNumber);
    }

    public function transferFunds(string $accountFrom, string $accountTo, float $amount)
    {
        if (!$this->transactionRepository->accountExists($accountTo)) {
            throw new \Exception("Recipient account does not exist: $accountTo");
        }

        if (!$this->transactionRepository->accountExists($accountFrom)) {
            throw new \Exception("Sender account does not exist: $accountFrom");
        }

        // Checking balace
        if (!$this->transactionRepository->hasSufficientFunds($accountFrom, $amount)) {
            throw new \Exception("Insufficient funds in account: $accountFrom");
        }

        // Transfer funds
        $this->transactionRepository->transfer($accountFrom, $accountTo, $amount);
    }
}