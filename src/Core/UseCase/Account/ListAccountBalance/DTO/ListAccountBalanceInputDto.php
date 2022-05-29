<?php

namespace Core\UseCase\Account\ListAccountBalance\DTO;

class ListAccountBalanceInputDto
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
