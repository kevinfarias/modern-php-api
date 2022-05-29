<?php

namespace Core\Domain\Account\Transaction\Interfaces;

use Core\Domain\Account\Account;

interface TransactionInterface {
    public function id(): string;
    public function amount(): float;
    public function createdAt(): \DateTime;
    public function validate(Account $accountA): bool;
    public function execute(Account $accountA): void;
}