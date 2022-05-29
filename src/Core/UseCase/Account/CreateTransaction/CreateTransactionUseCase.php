<?php

namespace Core\UseCase\Account\CreateTransaction;

use Core\Domain\Account\Account;
use Core\Domain\Account\Repository\AccountRepositoryInterface;
use Core\Domain\Account\Transaction\Transaction;
use Core\UseCase\Account\CreateTransaction\DTO\AccountData;
use Core\UseCase\Account\CreateTransaction\DTO\CreateTransactionInputDto;
use Core\UseCase\Account\CreateTransaction\DTO\CreateTransactionResponseDto;

class CreateTransactionUseCase
{
    private $accountRepository;

    public function __construct(
        AccountRepositoryInterface $accountRepository
    ) {
        $this->accountRepository = $accountRepository;
    }

    public function execute(CreateTransactionInputDto $input): CreateTransactionResponseDto
    {
        $origin = $this->accountRepository->findById($input->accountIdFrom);
        if (!$origin) {
            $accountOrigin = new Account($input->accountIdFrom, 0);
            $this->accountRepository->insert($accountOrigin);

            $origin = $this->accountRepository->findById($input->accountIdFrom);
        }

        $destination = $input->accountIdTo ? $this->accountRepository->findById($input->accountIdTo) : null;
        if ($input->accountIdTo && !$destination) {
            $accountDestination = new Account($input->accountIdTo, 0);
            $this->accountRepository->insert($accountDestination);

            $destination = $this->accountRepository->findById($input->accountIdTo);
        }

        $uuid = \Ramsey\Uuid\Uuid::uuid4();

        $transaction = Transaction::createTransactionFactory(
            $input->type,
            $uuid,
            $input->amount,
            null,
            $destination
        );

        $origin->addTransaction($transaction);
        $this->accountRepository->update($origin);
        if ($destination) {
            $destination->addTransaction($transaction);
            $this->accountRepository->update($destination);
        }

        return new CreateTransactionResponseDto(
            new AccountData($origin->id(), $origin->balance()),
            $destination ? new AccountData($destination->id(), $destination->balance()) : null
        );
    }
}