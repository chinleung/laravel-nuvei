<?php

namespace ChinLeung\Nuvei\Builders;

use ChinLeung\Nuvei\Card;
use ChinLeung\Nuvei\Charge;
use ChinLeung\Nuvei\Client;
use ChinLeung\Nuvei\Concerns\HasCustomer;
use ChinLeung\Nuvei\Constants\TerminalType;
use ChinLeung\Nuvei\Constants\TransactionType;
use ChinLeung\Nuvei\Contracts\Chargeable;
use ChinLeung\Nuvei\Exceptions\CardException;
use Illuminate\Support\Arr;

class ChargeBuilder extends Builder
{
    use HasCustomer;

    /**
     * The chargeable for the transaction.
     *
     * @var \ChinLeung\Nuvei\Contracts\Chargeable
     */
    protected $chargeable;

    /**
     * Set the amount of the charge as minor units.
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
     * Set the chargeable of the transaction.
     *
     * @param  \ChinLeung\Nuvei\Contracts\Chargeable  $chargeable
     * @return self
     */
    public function chargeable(Chargeable $chargeable): self
    {
        $this->chargeable = $chargeable;

        return $this;
    }

    /**
     * Set the currency of the transaction.
     *
     * @param  int  $currency
     * @return self
     */
    public function currency(int $currency): self
    {
        Arr::set($this->options, 'CURRENCY', $currency);

        return $this;
    }

    /**
     * Process the charge.
     *
     * @return \ChinLeung\Nuvei\Charge
     */
    public function create(): Charge
    {
        $response = resolve(Client::class)->send(
            'PAYMENT',
            $this->getRequestPayload()
        );

        if ($response->get('RESPONSECODE') != 'A') {
            throw new CardException(
                $response->get('RESPONSETEXT'),
                $response->get('BANKRESPONSECODE')
            );
        }

        return Charge::make($response);
    }

    /**
     * Retrieve the payload for the request.
     *
     * @return array
     */
    protected function getRequestPayload(): array
    {
        $chargeable = $this->chargeable->toPayload();

        $payload = [
            'ORDERID' => Arr::pull($this->options, 'ORDERID')
                ?? $this->generateId(24),
            'TERMINALID' => Arr::pull($this->options, 'TERMINALID'),
            'AMOUNT' => Arr::pull($this->options, 'AMOUNT') / 100,
            'DATETIME' => Arr::pull($this->options, 'DATETIME'),
            'CARDNUMBER' => Arr::get($chargeable, 'CARDNUMBER'),
            'CARDTYPE' => Arr::get($chargeable, 'CARDTYPE'),
        ];

        if ($this->chargeable instanceof Card) {
            $payload = array_merge($payload, array_filter([
                'CARDEXPIRY' => Arr::get($chargeable, 'CARDEXPIRY'),
                'CARDHOLDERNAME' => Arr::get($chargeable, 'CARDHOLDERNAME'),
            ]));
        }

        $payload = array_merge($payload, array_filter([
            'HASH' => 'TERMINALID:ORDERID:AMOUNT:DATETIME:SECRET',
            'CURRENCY' => Arr::pull($this->options, 'CURRENCY')
                ?? config('nuvei.currency'),
            'TERMINALTYPE' => Arr::pull(
                $this->options,
                'TERMINALTYPE',
                TerminalType::ECOMMERCE
            ),
            'TRANSACTIONTYPE' => Arr::pull(
                $this->options,
                'TRANSACTIONTYPE',
                TransactionType::ECOMMERCE
            ),
            'CVV' => Arr::get($chargeable, 'CVV'),
        ], static fn ($value) => $value !== null));

        return array_merge($payload, $this->options);
    }

    /**
     * Set the order id of the transaction.
     *
     * @param  string  $id
     * @return self
     */
    public function id(string $id): self
    {
        Arr::set($this->options, 'ORDERID', $id);

        return $this;
    }

    /**
     * Set the terminal type of the transaction.
     *
     * @param  int  $type
     * @return self
     */
    public function terminalType(int $type): self
    {
        Arr::set($this->options, 'TERMINALTYPE', $type);

        return $this;
    }

    /**
     * Set the transaction type of the transaction.
     *
     * @param  int  $type
     * @return self
     */
    public function transactionType(int $type): self
    {
        Arr::set($this->options, 'TRANSACTIONTYPE', $type);

        return $this;
    }
}
