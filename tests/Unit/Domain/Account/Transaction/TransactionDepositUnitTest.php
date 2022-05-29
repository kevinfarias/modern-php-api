<?php

namespace Tests\Unit\Domain\Account\Transaction;

use Core\Domain\Account\Account;
use Core\Domain\Account\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionDepositUnitTest extends TestCase
{
    public function testShouldCreateAValidDeposit()
    {
        $account = new Account('abcd', 100);
        $transaction = Transaction::createTransactionFactory('deposit', '123', 100, null, null);
        $this->assertTrue($transaction->validate($account));

        $transaction->execute($account);
        $this->assertEquals(200, $account->balance());
    }

    public function testShouldThrowErrorWithNegativeDeposit()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount must be greater than zero');
        $account = new Account('abcd', 0);
        $transaction = Transaction::createTransactionFactory('deposit', '123', -100, null, null);
        $transaction->validate($account);
    }
}