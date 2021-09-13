<?php

namespace ChinLeung\Nuvei\Tests;

use ChinLeung\Nuvei\Client;
use Orchestra\Testbench\TestCase;

class ClientTest extends TestCase
{
    /**
     * The demo mode will update the client's endpoint.
     *
     * @test
     * @return void
     */
    public function the_demo_mode_will_update_the_endpoint(): void
    {
        $client = new Client('id', 'user', 'pin', true);

        $this->assertEquals(
            'https://testpayments.nuvei.com/merchant/xmlpayment',
            $client->getEndpoint()
        );
    }
}
