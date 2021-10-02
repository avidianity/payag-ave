<?php

namespace Tests\Unit;

use Avidian\Semaphore\Client;
use Mockery;
use PHPUnit\Framework\TestCase;

class SemaphoreTest extends TestCase
{
    /**
     * @test
     */

    public function it_sends_a_sms_message()
    {
        /**
         * @var \Mockery\MockInterface|\Mockery\LegacyMockInterface
         */
        $client = Mockery::mock(Client::class);

        $response = [
            [
                "mssage_id" => 1234567,
                "user_id" => 99556,
                "user" => "user@your.org",
                "account_id" => 90290,
                "account" => "Your Account Name",
                "recipient" => "09991234567",
                "message" => "The message you sent",
                "sender_name" => "SEMAPHORE",
                "network" => "Globe",
                "status" => "Queued",
                "type" => "Single",
                "source" => "Api",
                "created_at" => "2016-01-01 00:01:01",
                "updated_at" => "2016-01-01 00:01:01",
            ]
        ];

        $client->shouldReceive('send')
            ->once()
            ->andReturn($response);

        $testResponse = $client->send('09991234567', 'The message you sent');

        $this->assertEquals($response, $testResponse);
    }
}
