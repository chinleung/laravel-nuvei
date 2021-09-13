<?php

namespace ChinLeung\Nuvei\Builders;

use ChinLeung\Nuvei\Client;
use ChinLeung\Nuvei\Refund;
use Illuminate\Support\Arr;

class RefundBuilder extends Builder
{
    /**
     * The id of the transaction to refund.
     *
     * @var string
     */
    protected $transaction;

    /**
     * Set the amount of the transaction as minor units to refund.
     *
     * @param  int  $amount
     * @return self
     */
    public function amount(int $amount): self
    {
        Arr::set($this->options, 'AMOUNT', $amount);

        return $this;
    }

    /**
     * Set the operator of the refund.
     *
     * @param  string  $operator
     * @return self
     */
    public function operator(string $operator): self
    {
        Arr::set($this->options, 'OPERATOR', $operator);

        return $this;
    }

    /**
     * Set the reason of the refund.
     *
     * @param  string  $reason
     * @return self
     */
    public function reason(string $reason): self
    {
        Arr::set($this->options, 'REASON', $reason);

        return $this;
    }

    /**
     * Process the charge.
     *
     * @link  https://helpdesk.nuvei.com/doku.php?id=developer:api_specification:xml_payment_features#standard_refund
     *
     * @return \ChinLeung\Nuvei\Refund
     */
    public function create(): Refund
    {
        $response = resolve(Client::class)
            ->send('REFUND', $this->getRequestPayload());

        return Refund::make($response);
    }

    /**
     * Retrieve the payload for the request.
     *
     * @return array
     */
    protected function getRequestPayload(): array
    {
        return array_merge([
            'UNIQUEREF' => $this->transaction,
            'TERMINALID' => Arr::pull($this->options, 'TERMINALID'),
            'AMOUNT' => Arr::pull($this->options, 'AMOUNT') / 100,
            'DATETIME' => Arr::pull($this->options, 'DATETIME'),
            'HASH' => 'TERMINALID:UNIQUEREF:AMOUNT:DATETIME:SECRET',
            'OPERATOR' => Arr::pull($this->options, 'OPERATOR'),
            'REASON' => Arr::pull($this->options, 'REASON'),
        ], $this->options);
    }

    /**
     * Set the id of the transction.
     *
     * @param  string  $transaction
     * @return self
     */
    public function transaction(string $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }
}
