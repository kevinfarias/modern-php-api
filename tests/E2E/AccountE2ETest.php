<?php

namespace Tests\E2E;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class AccountE2ETest extends TestCase
{
    public function testAllTheRoutes()
    {
        $client = new Client([
            'base_uri' => 'http://localhost:8081',
            'http_errors' => false,
        ]);

        # Reset state before starting tests
        $response = $client->post('/reset', []);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getBody()->getContents());

        # Get balance for non-existing account
        $response = $client->get('/balance?account_id=1234');
        $this->assertEquals('0', $response->getBody()->getContents());
        $this->assertEquals(404, $response->getStatusCode());
        
        # Create account with initial balance
        $response = $client->post('/event', [
            'json' => [
                'type'=>'deposit',
                'destination' => '100',
                'amount' => 10,
            ],
        ]);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals([
            'destination' => [
                'id' => '100',
                'balance' => 10,
            ],
        ], json_decode($response->getBody()->getContents(), true));

        # Deposit into existing account
        $response = $client->post('/event', [
            'json' => [
                'type'=>'deposit',
                'destination' => '100',
                'amount' => 10,
            ],
        ]);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals([
            'destination' => [
                'id' => '100',
                'balance' => 20,
            ],
        ], json_decode($response->getBody()->getContents(), true));

        # Get balance for existing account
        $response = $client->get('/balance?account_id=100');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('20', $response->getBody()->getContents());

        # Withdraw from non-existing account
        $response = $client->post('/event', [
            'json' => [
                'type'=>'withdraw',
                'destination' => '200',
                'amount' => 10,
            ],
        ]);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('0', $response->getBody()->getContents());

        # Withdraw from existing account
        $response = $client->post('/event', [
            'json' => [
                'type'=>'withdraw',
                'destination' => '100',
                'amount' => 5,
            ],
        ]);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals([
            'destination' => [
                'id' => '100',
                'balance' => 15,
            ],
        ], json_decode($response->getBody()->getContents(), true));

        # Transfer from existing account
        $response = $client->post('/event', [
            'json' => [
                'type'=>'transfer',
                'origin'=>'100',
                'destination' => '300',
                'amount' => 15,
            ],
        ]);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals([
            'origin' => [
                'id' => '100',
                'balance' => 0
            ],
            'destination' => [
                'id' => '300',
                'balance' => 15,
            ],
        ], json_decode($response->getBody()->getContents(), true));

        # Transfer from non-existing account
        $response = $client->post('/event', [
            'json' => [
                'type'=>'transfer',
                'origin'=>'200',
                'destination' => '300',
                'amount' => 15,
            ],
        ]);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('0', $response->getBody()->getContents());
    }
}