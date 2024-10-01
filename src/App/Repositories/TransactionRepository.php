<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Transaction;
use PDO;

class TransactionRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create(string $accountFrom, string $accountTo, float $amount): Transaction
    {
        $query = "INSERT INTO transactions (account_from, account_to, amount, status) VALUES (:account_from, :account_to, :amount, 'success')";
        $statement = $this->connection->prepare($query);
        $statement->execute([
            'account_from' => $accountFrom,
            'account_to' => $accountTo,
            'amount' => $amount,
        ]);

        return new Transaction(
            (int)$this->connection->lastInsertId(),
            $accountFrom,
            $accountTo,
            $amount,
            new \DateTime(),
            'success'
        );
    }

    public function getHistory(string $accountNumber): array
    {
        $query = "SELECT * FROM transactions WHERE account_from = :account_number OR account_to = :account_number ORDER BY transaction_date DESC";
        $statement = $this->connection->prepare($query);
        $statement->execute(['account_number' => $accountNumber]);

        $transactions = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $transactions[] = new Transaction(
                (int)$row['id'],
                $row['account_from'],
                $row['account_to'],
                (float)$row['amount'],
                new \DateTime($row['transaction_date']),
                $row['status']
            );
        }

        return $transactions;
    }

    public function hasSufficientFunds(string $accountNumber, float $amount): bool
    {
        $query = "SELECT balance FROM accounts WHERE account_number = :account_number";
        $statement = $this->connection->prepare($query);
        $statement->execute(['account_number' => $accountNumber]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        return $result && (float)$result['balance'] >= $amount;
    }

    public function transfer(string $accountFrom, string $accountTo, float $amount): void
    {
        try {
            $this->connection->beginTransaction();

            // Reduce the sender's balance
            $query = "UPDATE accounts SET balance = balance - :amount WHERE account_number = :account_from";
            $statement = $this->connection->prepare($query);
            $statement->execute(['amount' => $amount, 'account_from' => $accountFrom]);

            // Increase the recipient's balance
            $query = "UPDATE accounts SET balance = balance + :amount WHERE account_number = :account_to";
            $statement = $this->connection->prepare($query);
            $statement->execute(['amount' => $amount, 'account_to' => $accountTo]);

            $this->create($accountFrom, $accountTo, $amount);

            $this->connection->commit();
        } catch (\Exception $e) {
            // In case of error, cancel the transaction
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function accountExists(string $accountNumber): bool
    {
        $query = "SELECT 1 FROM accounts WHERE account_number = :account_number LIMIT 1";
        $statement = $this->connection->prepare($query);
        $statement->execute(['account_number' => $accountNumber]);
        return $statement->fetchColumn() !== false;
    }
}