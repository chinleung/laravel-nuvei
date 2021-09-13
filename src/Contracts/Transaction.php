<?php

namespace ChinLeung\Nuvei\Contracts;

interface Transaction
{
    /**
     * Retrieve the transaction id.
     *
     * @return string
     */
    public function getTransactionId(): string;
}
