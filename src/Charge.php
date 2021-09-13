<?php

namespace ChinLeung\Nuvei;

use ChinLeung\Nuvei\Builders\ChargeBuilder;
use ChinLeung\Nuvei\Concerns\Makeable;
use ChinLeung\Nuvei\Concerns\RequiresResponse;
use ChinLeung\Nuvei\Contracts\Chargeable;
use ChinLeung\Nuvei\Contracts\Transaction;

class Charge implements Transaction
{
    use Makeable, RequiresResponse;

    /**
     * Retrieve a new instance of the builder.
     *
     * @return \ChinLeung\Nuvei\Builders\ChargeBuilder
     */
    public static function builder(): ChargeBuilder
    {
        return ChargeBuilder::make();
    }

    /**
     * Create a charge.
     *
     * @param  \ChinLeung\Nuvei\Contracts\Chargeable  $chargeable
     * @param  int  $amount
     * @param  array  $options
     * @return self
     */
    public static function create(Chargeable $chargeable, int $amount, array $options = []): self
    {
        return static::builder()
            ->chargeable($chargeable)
            ->amount($amount)
            ->withOptions($options)
            ->create();
    }

    /**
     * Retrieve the transaction id of the charge.
     *
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->response->get('UNIQUEREF');
    }
}
