<?php

namespace App\Exceptions;

use Exception;

class OrderNotConfirmedException extends Exception
{
    public function __construct(
        string $message = 'Payment cannot be processed because the order is not confirmed.',
        int $code = 422
    ) {
        parent::__construct($message, $code);
    }
}
