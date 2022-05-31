<?php

namespace Infrastructure\Repository\Memory;

use Core\Domain\Account\Account;

class AccountInMemoryRepository implements \Core\Domain\Account\Repository\AccountRepositoryInterface
{
    private $accounts = [];
    
    static $instance = null;

    private function __construct() {
        $this->accounts = [];
    }

    public function insert(Account $account): Account
    {
        $this->accounts[$account->id()] = $account;
        return $account;
    }

    public function findById(string $id): Account | null
    {
        return $this->accounts[$id] ?? null;
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

    public function reset(): void
    {
        $this->accounts = [];
    }

    static function getInstance() {
        if (self::$instance) {
            return self::$instance;
        }

        self::$instance = new AccountInMemoryRepository();
        return self::$instance;
    }
}