<?php

namespace Tests\Unit\Domain\Account\Transaction;

use Core\Domain\Account\Account;
use Core\Domain\Account\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTransferUnitTest extends TestCase
{
    public function testShouldCreateAValidTransfer()
    {
        $accountTo = new Account('a', 0);
        $accountFrom = new Account('b', 100);
        $transaction = Transaction::createTransactionFactory('transfer', '123', 100, null, $accountFrom);
        $this->assertTrue($transaction->validate($accountTo));

        $transaction->execute($accountTo);
        $this->assertEquals(0, $accountFrom->balance());
        $this->assertEquals(100, $accountTo->balance());
    }

    public function testShouldThrowErrorWithoutAccountToTransfer()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Account from not found');
        $accountFrom = new Account('a', 100);
        $transaction = Transaction::createTransactionFactory('transfer', '123', 100, null, null);
        $transaction->validate($accountFrom);
    }

    public function testShouldThrowErrorWithTransferWithoutAmount()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The account does not have the desired balance to withdraw');
        
        $accountTo = new Account('a', 50);
        $accountFrom = new Account('b', 0);

        $transaction = Transaction::createTransactionFactory('transfer', '123', 51, null, $accountFrom);
        $transaction->validate($accountTo);
    }
}