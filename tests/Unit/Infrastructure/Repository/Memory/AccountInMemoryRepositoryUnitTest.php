<?php

namespace Unit\Infrastructure\Repository\Memory;

use Core\Domain\Account\Account;
use Infrastructure\Repository\Memory\AccountInMemoryRepository;
use PHPUnit\Framework\TestCase;

class AccountInMemoryRepositoryUnitTest extends TestCase
{
    public function testShouldInsertAndRetrieveAnAccount()
    {
        $account = new Account('id', 100);

        $repository = new AccountInMemoryRepository();
        $repository->insert($account);

        $this->assertEquals($account, $repository->findById($account->id()));

        $repository->delete($account);
        $this->assertEquals([], $repository->getAll());
    }
}