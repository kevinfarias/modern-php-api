<?php

namespace Infrastructure\Api\Controllers;

use Core\UseCase\Account\ListAccountBalance\DTO\ListAccountBalanceInputDto;
use Infrastructure\Repository\Memory\AccountInMemoryRepository;
use Core\UseCase\Account\ListAccountBalance\ListAccountBalanceUseCase;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class AccountBalanceController
{
    public function __construct(AccountInMemoryRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();
        $accountId = isset($params['account_id']) ? $params['account_id'] : null;
        if (!$accountId) {
            return Response::plaintext('')->withStatus(400);
        }

        try {
            $useCase = new ListAccountBalanceUseCase($this->accountRepository);
            $response = $useCase->execute(new ListAccountBalanceInputDto($accountId));

            return Response::plaintext((string)$response->balance())->withStatus(200);
        } catch (\Core\Domain\Shared\Errors\NotFoundError) {
            return Response::plaintext('0')->withStatus(404);
        } catch (\Throwable $e) {
            return Response::json(['error' => $e->getMessage()])->withStatus(500);
        }
    }
}