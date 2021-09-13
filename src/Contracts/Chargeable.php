<?php

namespace ChinLeung\Nuvei\Contracts;

interface Chargeable
{
    /**
     * Retrieve the information of the chargeable instance for a payload.
     *
     * @return array
     */
    public function toPayload(): array;
}
