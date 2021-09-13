<?php

namespace ChinLeung\Nuvei;

use ChinLeung\Nuvei\Builders\RefundBuilder;
use ChinLeung\Nuvei\Concerns\Makeable;
use ChinLeung\Nuvei\Concerns\RequiresResponse;
use ChinLeung\Nuvei\Contracts\Transaction;

class Refund implements Transaction
{
    use Makeable, RequiresResponse;

    /**
     * Retrieve a new instance of the builder.
     *
     * @return \ChinLeung\Nuvei\Builders\RefundBuilder
     */
    public static function builder(): RefundBuilder
    {
        return RefundBuilder::make();
    }

    /**
     * Create a refund.
     *
     * @param  string  $transaction
     * @param  int  $amount
     * @param  string  $reason
     * @param  string  $operator
     * @param  array  $options
     * @return self
     */
    public static function create(string $transaction, int $amount, string $reason, string $operator, array $options = []): self
    {
        return static::builder()
            ->transaction($transaction)
            ->amount($amount)
            ->reason($reason)
            ->operator($operator)
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
