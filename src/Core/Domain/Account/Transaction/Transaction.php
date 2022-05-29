<?php

namespace Core\Domain\Account\Transaction;

use Core\Domain\Account\Account;
use Core\Domain\Account\Transaction\Interfaces\TransactionInterface;

class Transaction {
    private $id;
    private $amount;
    private $createdAt;

    private function __construct(
        string $id,
        float $amount,
        \DateTime $createdAt = null
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->createdAt = $createdAt ?: new \DateTime();
    }

    public function id(): string {
        return $this->id;
    }

    public function amount(): float {
        return $this->amount;
    }

    public function createdAt(): \DateTime {
        return $this->createdAt;
    }

    static function createTransactionFactory(string $type, string $id, float $amount, \DateTime $createdAt = null, Account $accountTo = null): TransactionInterface {
        switch ($type) {
            case 'deposit':
                return new TransactionDeposit($id, $amount, $createdAt, $accountTo);
            case 'withdraw':
                return new TransactionWithdraw($id, $amount, $createdAt, $accountTo);
            case 'transfer':
                return new TransactionTransfer($id, $amount, $createdAt, $accountTo);
            default:
                throw new \InvalidArgumentException('Invalid transaction type');
        }
    }
}