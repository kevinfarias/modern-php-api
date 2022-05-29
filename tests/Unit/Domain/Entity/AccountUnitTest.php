<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Account;
use PHPUnit\Framework\TestCase;

class AccountUnitTest extends TestCase {
    public function test_constructor_should_set_id_and_balance() {
        $account = new Account('id', 100);
        $this->assertEquals('id', $account->id());
        $this->assertEquals(100, $account->balance());
    }

    public function test_constructor_should_set_created_at_if_provided() {
        $account = new Account('id', 100, '2020-01-01');
        $this->assertEquals('2020-01-01 00:00:00', $account->createdAt());
    }

    public function test_created_at_should_return_current_date_if_not_provided() {
        $account = new Account('id', 100);
        $this->assertEquals(date('Y-m-d'), substr($account->createdAt(), 0, 10));
    }
}