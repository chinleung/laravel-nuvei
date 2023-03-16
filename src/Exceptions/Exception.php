<?php

namespace ChinLeung\Nuvei\Exceptions;

use Exception as BaseException;

class Exception extends BaseException
{
    /**
     * Create a new instance of the exception.
     *
     * @param  string  $message
     * @param  string  $code
     */
    public function __construct(string $message, string $code)
    {
        if (! is_numeric($code)) {
            $message = "[{$code}]: {$message}";
            $code = (int) preg_replace('/[^\d]+/', '', $code);
        }

        parent::__construct($message, $code);
    }
}
