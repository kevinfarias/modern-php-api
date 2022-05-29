<?php

namespace Tests\Unit\Domain\Account;

use Core\Domain\Account\Account;
use Core\Domain\Account\Transaction\Transaction;
use Core\Domain\Account\Transaction\TransactionDeposit;
use PHPUnit\Framework\TestCase;

class TransactionUnitTest extends TestCase
{
    public function testShouldCreateAValidTransaction()
    {
        $account = new Account('id', 100);
        $transaction = Transaction::createTransactionFactory('deposit', 'id', 100, null, null);
        $this->assertTrue($transaction->validate($account));
    }

    public function testShouldThrowErrorWithInvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid transaction type');
        $account = new Account('id', 100);
        $transaction = Transaction::createTransactionFactory('invalid', 'id', 100, null, null);
        $transaction->validate($account);
    }
}