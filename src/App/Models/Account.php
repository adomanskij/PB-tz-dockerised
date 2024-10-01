<?php

declare(strict_types=1);

namespace App\Models;

class Account
{
    private string $accountNumber;
    private float $balance;
    private string $currency;

    public function __construct(string $accountNumber, float $balance, string $currency)
    {
        $this->accountNumber = $accountNumber;
        $this->balance = $balance;
        $this->currency = $currency;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }
}