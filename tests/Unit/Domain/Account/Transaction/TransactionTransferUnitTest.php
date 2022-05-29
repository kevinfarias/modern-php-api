<?php

namespace Tests\Unit\Domain\Account\Transaction;

use Core\Domain\Account\Account;
use Core\Domain\Account\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTransferUnitTest extends TestCase
{
    public function testShouldCreateAValidTransfer()
    {
        $account = new Account('a', 100);
        $accountB = new Account('b', 0);
        $transaction = Transaction::createTransactionFactory('transfer', '123', 100, null, $accountB);
        $this->assertTrue($transaction->validate($account));

        $transaction->execute($account);
        $this->assertEquals(0, $account->balance());
        $this->assertEquals(100, $accountB->balance());
    }

    public function testShouldThrowErrorWithoutAccountToTransfer()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Account to transfer not found');
        $account = new Account('a', 100);
        $transaction = Transaction::createTransactionFactory('transfer', '123', 100, null, null);
        $transaction->validate($account);
    }

    public function testShouldThrowErrorWithTransferWithoutAmount()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The account does not have the desired balance to withdraw');
        
        $account = new Account('a', 50);
        $accountB = new Account('b', 0);

        $transaction = Transaction::createTransactionFactory('transfer', '123', 51, null, $accountB);
        $transaction->validate($account);
    }
}