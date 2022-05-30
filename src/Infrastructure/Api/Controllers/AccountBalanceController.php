<?php

namespace Infrastructure\Api\Controllers;

use Core\UseCase\Account\ListAccountBalance\DTO\ListAccountBalanceInputDto;
use Infrastructure\Repository\Memory\AccountInMemoryRepository;
use Core\UseCase\Account\ListAccountBalance\ListAccountBalanceUseCase;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class AccountBalanceController
{
    public function __invoke(AccountInMemoryRepository &$accountRepository, ServerRequestInterface $request)
    {
        $accountId = $request->getAttribute('account_id');
        if (!$accountId) {
            return Response::json(['error' => 'Account id is required'], 400);
        }

        try {
            $useCase = new ListAccountBalanceUseCase($accountRepository);
            $response = $useCase->execute(new ListAccountBalanceInputDto($accountId));

            return Response::plaintext($response->balance(), 200);
        } catch (\Core\Domain\Shared\Errors\NotFoundError) {
            return Response::plaintext(0, 404);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Internal server error'], 500);
        }
    }
}