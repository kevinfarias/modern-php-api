<?php

namespace Core\UseCase\Account\ListAccountBalance;

class ListAccountBalanceResponseDto
{
    private $id;
    private $balance;

    public function __construct(string $id, float $balance)
    {
        $this->id = $id;
        $this->balance = $balance;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function balance(): float
    {
        return $this->balance;
    }
}