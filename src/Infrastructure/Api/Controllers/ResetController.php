<?php

namespace Infrastructure\Api\Controllers;

use Infrastructure\Repository\Memory\AccountInMemoryRepository;
use React\Http\Message\Response;

class ResetController
{
    public function __construct(AccountInMemoryRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function __invoke()
    {
        $this->accountRepository->reset();
        return Response::plaintext("OK", 200);
    }
}