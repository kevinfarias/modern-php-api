<?php

namespace Core\Domain\Account;

use Core\Domain\Account\Transaction\Interfaces\TransactionInterface;
use Core\Domain\Shared\Traits\EntityMethodsMagicsTrait;

class Account {
    use EntityMethodsMagicsTrait;

    private $id;
    private $balance;
    private $transactions = [];

    public function __construct(
        string $id,
        float $balance,
        \DateTime|string $createdAt = ''
    ) {
        $this->id = $id;
        $this->balance = $balance;
        $this->createdAt = $createdAt ? new \DateTime($createdAt) : new \DateTime();
        $this->validate();
    }

    public function validate()
    {
        if (!is_numeric($this->balance)) {
            throw new \InvalidArgumentException('Balance must be numeric');
        }

        if (!$this->createdAt instanceof \DateTime) {
            throw new \InvalidArgumentException('Created at must be instance of \DateTime');
        }

        return true;
    }

    public function balance() {
        return (float)$this->balance;
    }

    public function setBalance(float $value): void {
        $this->balance = $value;
    }

    public function getTransactions(): array {
        return $this->transactions;
    }

    public function addTransaction(TransactionInterface $transaction): void {
        try {
            $transaction->validate($this);
            $transaction->execute($this);
            $this->transactions[] = $transaction;
        } catch (\Exception $e) {
            throw new \Exception("Transaction validation failed: {$e}");
        }
    }
}