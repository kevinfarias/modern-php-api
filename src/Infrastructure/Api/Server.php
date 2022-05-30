<?php

namespace Infrastructure\Api;

use Infrastructure\Api\Controllers\AccountBalanceController;
use Infrastructure\Api\Controllers\InsertTransactionController;
use Infrastructure\Repository\Memory\AccountInMemoryRepository;

class Server
{
    private $app;

    public function __construct()
    {
        $this->app = $this->buildApp();
    }

    private function buildApp()
    {
        $accountRepository = new AccountInMemoryRepository();
        $container = new \FrameworkX\Container([
            AccountInMemoryRepository::class => fn() => $accountRepository,
        ]);

        $app = new \FrameworkX\App($container);

        $app->get('/balance', AccountBalanceController::class);

        $app->post('/event', InsertTransactionController::class);

        $app->post('/reset', function (AccountInMemoryRepository &$accountRepository) {
            $accountRepository->reset();
        });

        return $app;
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