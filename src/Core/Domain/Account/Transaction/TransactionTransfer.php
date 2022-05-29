<?php

namespace Core\Domain\Account\Transaction;

use Core\Domain\Account\Account;
use Core\Domain\Account\Transaction\Interfaces\TransactionInterface;
use Core\Domain\Account\Transaction\Transaction as SimpleTransaction;

class TransactionTransfer extends SimpleTransaction implements TransactionInterface
{
    public function validate(Account $accountA): bool
    {
        if ($this->amount() < 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }

        if ($accountA->balance() - $this->amount() < 0) {
            throw new \InvalidArgumentException('The account does not have the desired balance to withdraw');
        }

        if (!$this->accountTo()) {
            throw new \InvalidArgumentException('Account to transfer not found');
        }

        return true;
    }

    public function execute(Account &$accountA): void
    {
        $accountA->setBalance($accountA->balance() - $this->amount());
        $this->accountTo()->setBalance($this->accountTo()->balance() + $this->amount());
    }
}