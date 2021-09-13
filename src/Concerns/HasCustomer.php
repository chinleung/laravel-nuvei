<?php

namespace ChinLeung\Nuvei\Concerns;

use ChinLeung\Nuvei\Customer;

trait HasCustomer
{
    /**
     * The customer of the card.
     *
     * @var \ChinLeung\Nuvei\Customer
     */
    protected $customer;

    /**
     * Set the customer of the token.
     *
     * @param  \ChinLeung\Nuvei\Customer  $customer
     * @return self
     */
    public function customer(Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Retrieve the customer.
     *
     * @return \ChinLeung\Nuvei\Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }
}
