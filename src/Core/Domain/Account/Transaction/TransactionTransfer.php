<?php

namespace Core\Domain\Account\Transaction;

use Core\Domain\Account\Account;
use Core\Domain\Account\Transaction\Interfaces\TransactionInterface;
use Core\Domain\Account\Transaction\Transaction as SimpleTransaction;

class TransactionTransfer extends SimpleTransaction implements TransactionInterface
{
    public function validate(Account $accountTo): bool
    {
        if ($this->amount() < 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }

        $accountFrom = $this->accountFrom();
        if (!$accountFrom) {
            throw new \InvalidArgumentException('Account from not found');
        }

        if ($accountFrom->balance() - $this->amount() < 0) {
            throw new \InvalidArgumentException("The account does not have the desired balance to withdraw. Actual balance ({$accountFrom->id()}): {$accountFrom->balance()}. Desired withdraw: {$this->amount()}");
        }

        if (!$accountTo) {
            throw new \InvalidArgumentException('Account to transfer not found');
        }

        return true;
    }

    public function execute(Account &$accountTo): void
    {
        $accountTo->setBalance($accountTo->balance() + $this->amount());
        $this->accountFrom()->setBalance($this->accountFrom()->balance() - $this->amount());
    }
}