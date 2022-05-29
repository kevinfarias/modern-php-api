<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;

class Account {
    use MethodsMagicsTrait;

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
}