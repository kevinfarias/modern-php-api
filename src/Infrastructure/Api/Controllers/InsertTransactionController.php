<?php

namespace Infrastructure\Api\Controllers;

use Core\UseCase\Account\CreateTransaction\CreateTransactionUseCase;
use Core\UseCase\Account\CreateTransaction\DTO\CreateTransactionInputDto;
use Infrastructure\Repository\Memory\AccountInMemoryRepository;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class InsertTransactionController
{
    public function __construct(AccountInMemoryRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        try {
            $body = json_decode($request->getBody()->getContents(), true);
            $params = [
                'type' => isset($body['type']) ? $body['type'] : null,
                'destination' => isset($body['destination']) ? $body['destination'] : null,
                'balance' => isset($body['balance']) ? (float)$body['balance'] : null,
                'origin' => isset($body['origin']) ? $body['origin'] : '',
            ];

            $useCase = new CreateTransactionUseCase($this->accountRepository);
            $response = $useCase->execute(new CreateTransactionInputDto($params['type'], $params['destination'], $params['balance'], $params['origin']));

            return Response::json($response)->withStatus(201);
        } catch (\Core\Domain\Shared\Errors\NotFoundError $e) {
            return Response::plaintext($e->getMessage())->withStatus(404);
        } catch (\Throwable $e) {
            return Response::json(['error' => 'Internal server error: '.$e->getMessage()])->withStatus(500);
        }
    }
}