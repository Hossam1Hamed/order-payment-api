<?php

namespace App\Services\Payment\Exceptions;

use Exception;

class UnsupportedPaymentGatewayException extends Exception
{
    public function __construct(string $gateway, int $code = 400)
    {
        parent::__construct("The payment gateway [{$gateway}] is not supported or not active.", $code);
    }
}
