<?php

namespace Tests\E2E;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class AccountE2ETest extends TestCase
{
    public function testAllTheRoutes()
    {
        $client = new Client([
            'base_uri' => 'http://localhost:8080',
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
                'balance' => 10,
            ],
        ]);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('{"destination": {"id":"100", "balance":10}}', $response->getBody()->getContents());
    }
}