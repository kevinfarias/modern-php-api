<?php

namespace Core\Domain\Account\Transaction;

use Core\Domain\Account\Account;
use Core\Domain\Account\Transaction\Interfaces\TransactionInterface;
use Core\Domain\Account\Transaction\Transaction as SimpleTransaction;

class TransactionDeposit extends SimpleTransaction implements TransactionInterface
{
    public function validate(Account $accountA): bool {
        if ($this->amount() < 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }

        if ($accountA->balance() + $this->amount() < 0) {
            throw new \InvalidArgumentException('Balance cannot be negative');
        }

        return true;
    }

    public function execute(Account $accountA): void
    {
        $accountA->balance($accountA->balance() + $this->amount());
    }
}