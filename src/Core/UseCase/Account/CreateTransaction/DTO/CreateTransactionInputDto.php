<?php

namespace Core\UseCase\Account\CreateTransaction\DTO;

class CreateTransactionInputDto
{
    public function __construct(
        public string $accountIdTo, 
        public string $type, 
        public float $amount, 
        public string $accountIdFrom = ''
    ) {
    }
}