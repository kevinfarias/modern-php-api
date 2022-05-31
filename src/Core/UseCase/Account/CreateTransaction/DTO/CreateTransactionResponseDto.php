<?php

namespace Core\UseCase\Account\CreateTransaction\DTO;

class AccountData {
    public function __construct(
        public string $id,
        public float $balance
    ) {
        
    }
}

class CreateTransactionResponseDto
{
    public function __construct(
        public ?AccountData $origin = null,
        public ?AccountData $destination = null
    )
    {
        
    }

    public function notNullValues()
    {
        return array_filter([
            'origin' => $this->origin,
            'destination' => $this->destination
        ]) ?: null;
    }
}