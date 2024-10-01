<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Controllers\AccountController;
use App\Services\AccountService;
use App\Services\TransactionService;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Models\Account;
use App\Models\Transaction;

class AccountTest extends TestCase
{
    private AccountController $accountController;
    private AccountService $accountService;
    private TransactionService $transactionService;

    protected function setUp(): void
    {
        // Create mocks for repo's
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->transactionRepository = $this->createMock(TransactionRepository::class);

        $this->accountService = new AccountService($this->accountRepository);
        $this->transactionService = new TransactionService($this->transactionRepository);
        $this->accountController = new AccountController($this->accountService);
    }

    public function testCreateAccount(): void
    {
        $mockAccount = new Account('1234567890', 1000.00, 'USD');

        // Mock for createAccount method
        $this->accountRepository->method('create')
            ->willReturn($mockAccount);

        $response = $this->accountController->create([
            'account_number' => '1234567890',
            'balance' => 1000.00,
            'currency' => 'USD'
        ]);

        $this->assertStringContainsString('Account created: 1234567890', $response);
    }

    public function testTransfer(): void
    {
        $accountFrom = '1234567890';
        $accountTo = '0987654321';
        $amount = 500.00;

        // Mock for accounts
        $this->transactionRepository->method('accountExists')
            ->willReturnMap([
                [$accountFrom, true],
                [$accountTo, true]
            ]);
        
        // Mock for funds
        $this->transactionRepository->method('hasSufficientFunds')
            ->with($accountFrom, $amount)
            ->willReturn(true);

        // Mock for transfer method
        $this->transactionRepository->expects($this->once())
            ->method('transfer')
            ->with($accountFrom, $accountTo, $amount);

        $this->transactionService->transferFunds($accountFrom, $accountTo, $amount);
        $this->assertTrue(true);
    }

    public function testGetTransactionHistory(): void
    {
        $mockTransactions = [
            new Transaction(1, '1234567890', '0987654321', 300.00, new \DateTime(), 'success'),
            new Transaction(2, '1234567890', '0987654321', 500.00, new \DateTime(), 'success'),
        ];

        // Mock for getHistory mothod
        $this->transactionRepository->method('getHistory')
            ->willReturn($mockTransactions);

        $response = $this->transactionService->getTransactionHistory('1234567890');

        $this->assertCount(2, $response);
        $this->assertEquals('1234567890', $response[0]->getAccountFrom());
    }
}