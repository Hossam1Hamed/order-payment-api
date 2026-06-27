<?php

namespace App\Exceptions;

use Exception;

class OrderHasPaymentsException extends Exception
{
    public function __construct(
        string $message = 'Order cannot be deleted because it has associated payments.',
        int $code = 422,
    ) {
        parent::__construct($message, $code);
    }
}
