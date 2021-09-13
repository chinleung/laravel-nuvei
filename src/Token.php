<?php

namespace ChinLeung\Nuvei;

use ChinLeung\Nuvei\Builders\TokenBuilder;
use ChinLeung\Nuvei\Concerns\Makeable;
use ChinLeung\Nuvei\Contracts\Chargeable;

class Token implements Chargeable
{
    use Makeable;

    /**
     * The card type of the token.
     *
     * @var string
     */
    protected $type;

    /**
     * The value of the generated token.
     *
     * @var string
     */
    protected $value;

    /**
     * Create a new token instance.
     *
     * @param  string  $value
     * @param  string|null  $type
     */
    public function __construct(string $value, string $type = null)
    {
        $this->value = $value;
        $this->type = $type ?? 'VISA';
    }

    /**
     * Retrieve a new instance of the builder.
     *
     * @return \ChinLeung\Nuvei\Builders\TokenBuilder
     */
    public static function builder(): TokenBuilder
    {
        return TokenBuilder::make();
    }

    /**
     * Create a new token.
     *
     * @param  \ChinLeung\Nuvei\Card  $card
     * @return \ChinLeung\Nuvei\Token
     */
    public static function create(Card $card): Token
    {
        return static::builder()->card($card)->create();
    }

    /**
     * Retrieve the value of the token.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Create a new token and save it in the token vault.
     *
     * @param  \ChinLeung\Nuvei\Card  $card
     * @return \ChinLeung\Nuvei\Token
     */
    public static function save(Card $card): Token
    {
        return static::builder()->card($card)->save();
    }

    /**
     * Retrieve the information of the card for a payload.
     *
     * @return array
     */
    public function toPayload(): array
    {
        return [
            'CARDNUMBER' => $this->value,
            'CARDTYPE' => $this->type,
        ];
    }
}
