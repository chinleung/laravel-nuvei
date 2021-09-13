<?php

namespace ChinLeung\Nuvei\Builders;

use ChinLeung\Nuvei\Card;
use ChinLeung\Nuvei\Client;
use ChinLeung\Nuvei\Token;
use Illuminate\Support\Arr;

class TokenBuilder extends Builder
{
    /**
     * The card that should be used for the token.
     *
     * @var \ChinLeung\Nuvei\Card
     */
    protected $card;

    /**
     * Set the card of the token.
     *
     * @param  \ChinLeung\Nuvei\Card  $card
     * @return self
     */
    public function card(Card $card): self
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Create the token.
     *
     * @link  https://helpdesk.nuvei.com/doku.php?id=developer:api_specification:xml_secure_card_features#registration
     *
     * @return \ChinLeung\Nuvei\Token
     */
    public function create(): Token
    {
        $response = resolve(Client::class)->send(
            'SECURECARDREGISTRATION',
            $this->getRequestPayload()
        );

        return Token::make(
            $response->get('CARDREFERENCE'),
            $this->card->getType()
        );
    }

    /**
     * Retrieve the payload for the request.
     *
     * @return array
     */
    public function getRequestPayload(): array
    {
        $payload = [
            'MERCHANTREF' => Arr::pull($this->options, 'MERCHANTREF')
                ?? $this->generateId(48),
            'TERMINALID' => Arr::pull($this->options, 'TERMINALID'),
            'DATETIME' => Arr::pull($this->options, 'DATETIME'),
            'CARDNUMBER' => $this->card->getNumber(),
            'CARDEXPIRY' => $this->card->getExpiry(),
            'CARDTYPE' => $this->card->getType(),
            'CARDHOLDERNAME' => $this->card->getName(),
            'HASH' => 'TERMINALID:MERCHANTREF:DATETIME:CARDNUMBER:CARDEXPIRY:CARDTYPE:CARDHOLDERNAME:SECRET',
            'CVV' => $this->card->getCvc(),
        ];

        if ($customer = $this->card->getCustomer()) {
            $payload = array_merge($payload, array_filter([
                'EMAIL' => $customer->getEmail(),
                'PHONE' => $customer->getPhone(),
            ]));
        }

        return array_merge($payload, $this->options);
    }

    /**
     * Save the token.
     *
     * @return \ChinLeung\Nuvei\Token
     */
    public function save(): Token
    {
        return $this->create();
    }
}
