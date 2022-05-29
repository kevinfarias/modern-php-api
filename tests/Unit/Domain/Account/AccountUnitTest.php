<?php

namespace Tests\Unit\Domain\Account;

use Core\Domain\Account\Account;
use PHPUnit\Framework\TestCase;

class AccountUnitTest extends TestCase {
    public function testConstructorShouldSetIdAndBalance() {
        $account = new Account('id', 100);
        $this->assertEquals('id', $account->id());
        $this->assertEquals(100, $account->balance());
    }

    public function testConstructorShouldSetCreatedAtIfProvided() {
        $account = new Account('id', 100, '2020-01-01');
        $this->assertEquals('2020-01-01 00:00:00', $account->createdAt());
    }

    public function testCreatedAtShouldReturnCurrentDateIfNotProvided() {
        $account = new Account('id', 100);
        $this->assertEquals(date('Y-m-d'), substr($account->createdAt(), 0, 10));
    }
}