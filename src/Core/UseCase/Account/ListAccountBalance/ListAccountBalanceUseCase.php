<?php

namespace Core\UseCase\Account\ListAccountBalance;

use Core\Domain\Account\Repository\AccountRepositoryInterface;
use Core\Domain\Shared\Errors\NotFoundError;
use Core\UseCase\Account\ListAccountBalance\DTO\{ListAccountBalanceInputDto, ListAccountBalanceResponseDto};

class ListAccountBalanceUseCase
{
    private $repository;

    public function __construct(AccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListAccountBalanceInputDto $inputDto): ListAccountBalanceResponseDto
    {
        $account = $this->repository->findById($inputDto->id());

        if (!$account) {
            throw new NotFoundError();
        }

        return new ListAccountBalanceResponseDto($account->id(), $account->balance());
    }
}