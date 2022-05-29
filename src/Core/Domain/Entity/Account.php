<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;

class Account {
    use MethodsMagicsTrait;

    public function __construct(
        private string $id,
        private float $balance,
        private \DateTime|string $createdAt = ''
    ) {
        $this->createdAt = $this->createdAt ? new \DateTime($this->createdAt) : new \DateTime();
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
}