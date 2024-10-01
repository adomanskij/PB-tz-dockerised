<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TransactionService;

class TransactionController
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function create(array $data)
    {
        $accountFrom = $data['account_from'];
        $accountTo = $data['account_to'];
        $amount = (float)$data['amount'];

        return $this->transactionService->createTransaction($accountFrom, $accountTo, $amount);
    }

    public function history(string $accountNumber)
    {
        $transactions = $this->transactionService->getTransactionHistory($accountNumber);
        if (empty($transactions)) {
            return "No transactions found for account: $accountNumber.";
        }

        $result = "Transaction History for account $accountNumber:\n";
        foreach ($transactions as $transaction) {
            $result .= "From: {$transaction->getAccountFrom()}, To: {$transaction->getAccountTo()}, Amount: {$transaction->getAmount()}, Date: {$transaction->getTransactionDate()->format('Y-m-d H:i:s')}, Status: {$transaction->getStatus()}\n";
        }
        return nl2br($result); // Перетворює нові рядки в <br> для HTML
    }

    public function transfer(array $data)
    {
        $accountFrom = $data['account_from'];
        $accountTo = $data['account_to'];
        $amount = (float)$data['amount'];

        try {
            $this->transactionService->transferFunds($accountFrom, $accountTo, $amount);
            return "Transfer successful from $accountFrom to $accountTo of amount $amount.";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}