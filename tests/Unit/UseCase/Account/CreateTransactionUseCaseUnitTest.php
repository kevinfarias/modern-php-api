<?php

namespace Unit\UseCase\Account;


use Core\Domain\Account\Account;
use Core\Domain\Account\Repository\AccountRepositoryInterface;
use Core\UseCase\Account\CreateTransactionUseCase\DTO\{CreateTransactionUseCaseInputDto, CreateTransactionUseCaseResponseDto};
use Core\UseCase\Account\CreateTransactionUseCase\CreateTransactionUseCaseUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateTransactionUseCaseUnitTest extends TestCase
{
    public function testShouldReturnASuccessfulTransaction()
    {
        $account = new Account('id', 100);

        $mockRepo = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockRepo->shouldReceive('findById')
                ->with($account->id())
                ->andReturn($account);

        $dto = new CreateTransactionUseCaseInputDto($account->id(), 'deposit', 100, null, null);

        $useCase = new CreateTransactionUseCaseUseCase($mockRepo);
        $response = $useCase->execute($dto);

        $this->assertInstanceOf(CreateTransactionUseCaseResponseDto::class, $response);
        $this->assertEquals($account->balance(), $response->balance());
        $this->assertEquals($account->id(), $response->id());
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}