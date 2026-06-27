<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Dto\PaymentResponse;
use Illuminate\Support\Facades\Log;

class CreditCardGateway implements PaymentGatewayInterface
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Charge a specific amount.
     */
    public function charge(float $amount, array $data): PaymentResponse
    {
        Log::info('Processing Credit Card payment', [
            'amount' => $amount,
            'card_last_four' => isset($data['card_number']) ? substr($data['card_number'], -4) : null,
        ]);

        $cardNumber = $data['card_number'] ?? '';
        if (str_ends_with($cardNumber, '0000')) {
            return PaymentResponse::failed('Credit card charge declined: Insufficient funds.', [
                'gateway' => $this->getName(),
                'error_code' => 'card_declined',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        $transactionId = 'cc_' . bin2hex(random_bytes(10));

        return PaymentResponse::success($transactionId, [
            'gateway' => $this->getName(),
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Refund a specific transaction amount.
     */
    public function refund(string $paymentGatewayId, float $amount): PaymentResponse
    {
        Log::info('Refunding Credit Card payment', [
            'transaction_id' => $paymentGatewayId,
            'amount' => $amount,
        ]);

        $refundId = 'cc_ref_' . bin2hex(random_bytes(10));

        return PaymentResponse::success($refundId, [
            'gateway' => $this->getName(),
            'refund_id' => $refundId,
            'amount' => $amount,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get the name of the payment gateway.
     */
    public function getName(): string
    {
        return 'credit_card';
    }
}
