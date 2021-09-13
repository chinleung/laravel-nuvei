<?php

namespace ChinLeung\Nuvei;

use ChinLeung\Nuvei\Concerns\HasCustomer;
use ChinLeung\Nuvei\Concerns\Makeable;
use ChinLeung\Nuvei\Contracts\Chargeable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Card implements Chargeable
{
    use HasCustomer, Makeable;

    /**
     * The security code of the card.
     *
     * @var string
     */
    protected $cvc;

    /**
     * The expiration date of the card.
     *
     * @var string
     */
    protected $expiry;

    /**
     * The card number.
     *
     * @var string
     */
    protected $number;

    /**
     * The type of the card.
     *
     * @var string
     */
    protected $type;

    /**
     * Create a new instance of the card.
     *
     * @param  string  $number
     * @param  string  $expiry
     * @param  string  $cvc
     * @param  string  $type
     */
    public function __construct(string $number, string $expiry, string $cvc, string $type = null)
    {
        $this->number = $number;
        $this->expiry = $expiry;
        $this->cvc = $cvc;
        $this->type = $type ?? $this->determineType();
    }

    /**
     * Create a test American Express card.
     *
     * @return self
     */
    public static function americanExpress(): self
    {
        return static::fake('374200000000004');
    }

    /**
     * Determine the type of the card.
     *
     * @return string
     */
    public function determineType(): string
    {
        if (Str::startsWith($this->number, '3')) {
            return 'AMEX';
        }

        if (Str::startsWith($this->number, '4')) {
            return 'VISA';
        }

        if (Str::startsWith($this->number, '5')) {
            return 'MASTERCARD';
        }

        return 'UNKNOWN';
    }

    /**
     * Generate a card instance with a fake number.
     *
     * @param  string  $number
     * @return self
     */
    public static function fake(string $number): self
    {
        return new static(
            $number,
            Carbon::now()->addYear()->format('my'),
            mt_rand(100, 999)
        );
    }

    /**
     * Retrieve the cvc of the card.
     *
     * @return string
     */
    public function getCvc(): string
    {
        return $this->cvc;
    }

    /**
     * Retrieve the expiration of the card.
     *
     * @return string
     */
    public function getExpiry(): string
    {
        return $this->expiry;
    }

    /**
     * Retrieve the name of the card holder.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return optional($this->getCustomer())->getName();
    }

    /**
     * Retrieve the number of the card.
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Retrieve the type of the card.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Create a test MasterCard card.
     *
     * @return self
     */
    public static function masterCard(): self
    {
        return static::fake('5404000000000001');
    }

    /**
     * Retrieve the payload of the card.
     *
     * @return array
     */
    public function toPayload(): array
    {
        return [
            'CARDNUMBER' => $this->getNumber(),
            'CARDTYPE' => $this->getType(),
            'CARDEXPIRY' => $this->getExpiry(),
            'CARDHOLDERNAME' => $this->getName(),
            'CVV' => $this->getCvc(),
        ];
    }

    /**
     * Create a test Visa card.
     *
     * @link  https://helpdesk.nuvei.com/doku.php?id=developer:integration_docs:testing-guide#testing_verification
     *
     * @return self
     */
    public static function visa(): self
    {
        return static::fake(collect([
            '4444333322221111',
            '4005530000000086',
        ])->random());
    }
}
