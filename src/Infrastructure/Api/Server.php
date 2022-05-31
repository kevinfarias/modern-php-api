<?php

namespace Infrastructure\Api;

use Infrastructure\Api\Controllers\AccountBalanceController;
use Infrastructure\Api\Controllers\InsertTransactionController;
use Infrastructure\Api\Controllers\ResetController;
use Infrastructure\Repository\Memory\AccountInMemoryRepository;

class Server
{
    private $app;

    public function __construct()
    {
        $this->buildApp();
    }

    private function buildApp(): void
    {
        $container = new \FrameworkX\Container([
            AccountInMemoryRepository::class => fn() => AccountInMemoryRepository::getInstance(),
        ]);

        $app = new \FrameworkX\App($container);

        $app->get('/balance', AccountBalanceController::class);

        $app->post('/event', InsertTransactionController::class);

        $app->post('/reset', ResetController::class);

        $this->app = $app;
    }

    public function getApp(): \FrameworkX\App
    {
        return $this->app;
    }

    public function run()
    {
        $this->app->run();
    }
}