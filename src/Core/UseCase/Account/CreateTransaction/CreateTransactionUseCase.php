<?php

namespace Core\UseCase\Account\CreateTransaction;

use Core\Domain\Account\Account;
use Core\Domain\Account\Repository\AccountRepositoryInterface;
use Core\Domain\Account\Transaction\Transaction;
use Core\Domain\Shared\Errors\NotFoundError;
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

        $destination = $this->accountRepository->findById($input->accountIdTo);
        if (!$destination && $input->type != "withdraw") {
            $accountDestination = new Account($input->accountIdTo, 0);
            $this->accountRepository->insert($accountDestination);

            $destination = $this->accountRepository->findById($input->accountIdTo);
        }
        if (!$destination) {
            throw new NotFoundError();
        }

        // It is a transfer
        if ($input->accountIdFrom) {
            $origin = $this->accountRepository->findById($input->accountIdFrom);
            if (!$origin) {
                throw new NotFoundError();
            }
        } else {
            $origin = null;
        }

        $uuid = \Ramsey\Uuid\Uuid::uuid4();

        $transaction = Transaction::createTransactionFactory(
            $input->type,
            $uuid,
            $input->amount,
            null,
            $origin
        );

        $destination->addTransaction($transaction);
        $this->accountRepository->update($destination);
        if ($origin) {
            $origin->addTransaction($transaction, false);
            $this->accountRepository->update($destination);
        }

        if ($input->type == "withdraw") {
            $origin = $destination;
            $destination = null;
        }

        return new CreateTransactionResponseDto(
            $origin ? new AccountData($origin->id(), $origin->balance()) : null,
            $destination ? new AccountData($destination->id(), $destination->balance()) : null
        );
    }
}