<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;
use PDO;

class AccountRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create(string $accountNumber, float $balance, string $currency): Account
    {
        try {
            $query = "INSERT INTO accounts (account_number, balance, currency) VALUES (:account_number, :balance, :currency)";
            $statement = $this->connection->prepare($query);
            $statement->execute([
                'account_number' => $accountNumber,
                'balance' => $balance,
                'currency' => $currency,
            ]);

            return new Account($accountNumber, $balance, $currency);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23505') { // Unique violation
                throw new \Exception("Account with account number {$accountNumber} already exists.");
            }
            throw $e;
        }
    }

    public function findByAccountNumber(string $accountNumber): ?Account
    {
        $query = "SELECT * FROM accounts WHERE account_number = :account_number";
        $statement = $this->connection->prepare($query);
        $statement->execute(['account_number' => $accountNumber]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }

        return new Account($result['account_number'], (float)$result['balance'], $result['currency']);
    }

    public function delete(string $accountNumber): bool
    {
        $query = "DELETE FROM accounts WHERE account_number = :account_number";
        $statement = $this->connection->prepare($query);
        return $statement->execute(['account_number' => $accountNumber]);
    }
}