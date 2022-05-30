<?php

namespace Infrastructure\Api\Controllers;

use Core\UseCase\Account\CreateTransaction\CreateTransactionUseCase;
use Core\UseCase\Account\CreateTransaction\DTO\CreateTransactionInputDto;
use Infrastructure\Repository\Memory\AccountInMemoryRepository;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class InsertTransactionController
{
    public function __invoke(AccountInMemoryRepository &$accountRepository, ServerRequestInterface $request)
    {
        try {
            $useCase = new CreateTransactionUseCase($accountRepository);
            $response = $useCase->execute(new CreateTransactionInputDto($request->getAttribute('origin'), $request->getAttribute('type'), (float)$request->getAttribute('amount'), $request->getAttribute('destination')));

            return Response::json($response, 201);
        } catch (\Core\Domain\Shared\Errors\NotFoundError) {
            return Response::plaintext(0, 404);
        } catch (\Exception $e) {
            return Response::json(['error' => 'Internal server error'], 500);
        }
    }
}