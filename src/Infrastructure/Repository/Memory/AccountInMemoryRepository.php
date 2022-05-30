<?php

namespace Infrastructure\Repository\Memory;

use Core\Domain\Account\Account;

class AccountInMemoryRepository implements \Core\Domain\Account\Repository\AccountRepositoryInterface
{
    private $accounts = [];

    public function insert(Account $account): Account
    {
        $this->accounts[$account->id()] = $account;
        return $account;
    }

    public function findById(string $id): Account
    {
        if (!isset($this->accounts[$id])) {
            throw new \Core\Domain\Shared\Errors\NotFoundError();
        }
        return $this->accounts[$id];
    }

    public function update(Account $account): Account
    {
        $this->accounts[$account->id()] = $account;
        return $account;
    }

    public function delete(Account $account): void
    {
        unset($this->accounts[$account->id()]);
    }

    public function getAll(): array
    {
        return $this->accounts;
    }
}