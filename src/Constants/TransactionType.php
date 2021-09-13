<?php

namespace ChinLeung\Nuvei\Constants;

abstract class TransactionType
{
    public const CARDHOLDER_PRESENT = 0;
    public const RECURRING = 2;
    public const MOTO = 4;
    public const ECOMMERCE = 7;
}
