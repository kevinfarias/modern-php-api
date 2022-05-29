<?php

namespace Core\Domain\Account\Repository;

use Core\Domain\Account\Account;

interface AccountRepositoryInterface
{
    public function insert(Account $account): Account;
    public function findById(string $id): Account | null;
    public function update(Account $account): Account;
    public function delete(Account $account): void;
}