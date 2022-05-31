<?php

namespace Unit\UseCase\Account;


use Core\Domain\Account\Account;
use Core\Domain\Account\Repository\AccountRepositoryInterface;
use Core\UseCase\Account\CreateTransaction\CreateTransactionUseCase;
use Core\UseCase\Account\CreateTransaction\DTO\{CreateTransactionInputDto, CreateTransactionResponseDto};
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
        $mockRepo->shouldReceive('update')
                 ->with($account)
                 ->andReturn($account);

        $dto = new CreateTransactionInputDto($account->id(), 'deposit', 100, "");

        $useCase = new CreateTransactionUseCase($mockRepo);
        $response = $useCase->execute($dto);

        $this->assertInstanceOf(CreateTransactionResponseDto::class, $response);
        $this->assertEquals(200, $response->destination->balance);
        $this->assertEquals($account->id(), $response->destination->id);
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}