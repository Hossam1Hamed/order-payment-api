<?php

namespace App\Exceptions;

use Exception;

class PaymentFailedException extends Exception
{
    protected ?array $errors;

    public function __construct(
        string $message = 'Payment processing failed.',
        ?array $errors = null,
        int $code = 400
    ) {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
