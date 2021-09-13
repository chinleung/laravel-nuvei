<?php

namespace ChinLeung\Nuvei\Tests;

use ChinLeung\Nuvei\Card;
use Orchestra\Testbench\TestCase;

class CardTest extends TestCase
{
    /**
     * A card can be converted to a request payload properly.
     *
     * @test
     * @return void
     */
    public function the_payload_will_be_correct(): void
    {
        $card = Card::make('4444333322221111', '1221', '345');

        $this->assertEquals([
            'CARDNUMBER' => '4444333322221111',
            'CARDTYPE' => 'VISA',
            'CARDEXPIRY' => '1221',
            'CARDHOLDERNAME' => null,
            'CVV' => '345',
        ], $card->toPayload());
    }
}
