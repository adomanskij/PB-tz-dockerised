<?php

declare(strict_types=1);

namespace App\Models;

class Transaction
{
    private int $id;
    private string $accountFrom;
    private string $accountTo;
    private float $amount;
    private \DateTime $transactionDate;
    private string $status;

    public function __construct(
        int $id,
        string $accountFrom,
        string $accountTo,
        float $amount,
        \DateTime $transactionDate,
        string $status
    ) {
        $this->id = $id;
        $this->accountFrom = $accountFrom;
        $this->accountTo = $accountTo;
        $this->amount = $amount;
        $this->transactionDate = $transactionDate;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAccountFrom(): string
    {
        return $this->accountFrom;
    }

    public function getAccountTo(): string
    {
        return $this->accountTo;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTransactionDate(): \DateTime
    {
        return $this->transactionDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}