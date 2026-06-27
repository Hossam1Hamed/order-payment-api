<?php

namespace App\Services\Payment\Dto;

class PaymentResponse
{
    public function __construct(
        public bool $success,
        public ?string $transactionId = null,
        public ?array $rawResponse = null,
        public ?string $errorMessage = null
    ) {}

    /**
     * Create a successful payment response.
     */
    public static function success(string $transactionId, ?array $rawResponse = null): self
    {
        return new self(
            success: true,
            transactionId: $transactionId,
            rawResponse: $rawResponse
        );
    }

    /**
     * Create a failed payment response.
     */
    public static function failed(string $errorMessage, ?array $rawResponse = null): self
    {
        return new self(
            success: false,
            errorMessage: $errorMessage,
            rawResponse: $rawResponse
        );
    }
}
