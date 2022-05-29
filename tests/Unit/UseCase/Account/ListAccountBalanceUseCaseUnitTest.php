<?php

namespace Unit\UseCase\Account;

use Core\Domain\Account\Account;
use Core\Domain\Account\Repository\AccountRepositoryInterface;
use Core\UseCase\Account\ListAccountBalance\DTO\{ListAccountBalanceInputDto, ListAccountBalanceResponseDto};
use Core\UseCase\Account\ListAccountBalance\ListAccountBalanceUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListAccountBalanceUseCaseUnitTest extends TestCase
{
    public function testShouldReturnAccountBalance()
    {
        $account = new Account('id', 100);

        $mockRepo = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockRepo->shouldReceive('findById')
                 ->with($account->id())
                 ->andReturn($account);

        $dto = new ListAccountBalanceInputDto($account->id());

        $useCase = new ListAccountBalanceUseCase($mockRepo);
        $response = $useCase->execute($dto);
        
        $this->assertInstanceOf(ListAccountBalanceResponseDto::class, $response);
        $this->assertEquals($account->balance(), $response->balance());
        $this->assertEquals($account->id(), $response->id());
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}