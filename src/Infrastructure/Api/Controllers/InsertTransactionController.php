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
                'type' => isset($body['type']) ? $body['type'] : '',
                'destination' => isset($body['destination']) ? $body['destination'] : '',
                'amount' => isset($body['amount']) ? (float)$body['amount'] : null,
                'origin' => isset($body['origin']) ? $body['origin'] : '',
            ];

            $useCase = new CreateTransactionUseCase($this->accountRepository);
            $response = $useCase->execute(new CreateTransactionInputDto($params['destination'], $params['type'], $params['amount'], $params['origin']));

            return Response::json($response->notNullValues())->withStatus(201);
        } catch (\Core\Domain\Shared\Errors\NotFoundError $e) {
            return Response::plaintext('0')->withStatus(404);
        } catch (\Throwable $e) {
            return Response::json(['error' => 'Internal server error: '.$e->getMessage()])->withStatus(500);
        }
    }
}