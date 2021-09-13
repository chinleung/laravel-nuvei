<?php

namespace ChinLeung\Nuvei\Tests;

use ChinLeung\Nuvei\Token;
use Orchestra\Testbench\TestCase;

class TokenTest extends TestCase
{
    /**
     * A token can be converted to a request payload properly.
     *
     * @test
     * @return void
     */
    public function the_payload_will_be_correct(): void
    {
        $token = Token::make('123456789');

        $this->assertEquals([
            'CARDNUMBER' => '123456789',
        ], $token->toPayload());
    }
}
