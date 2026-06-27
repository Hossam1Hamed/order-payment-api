<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Dto\PaymentResponse;
use Illuminate\Support\Facades\Log;

class PayPalGateway implements PaymentGatewayInterface
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
        Log::info('Processing PayPal payment', [
            'amount' => $amount,
            'email' => $data['paypal_email'] ?? null,
        ]);

        $email = $data['paypal_email'] ?? '';
        if (str_contains($email, 'fail') || str_contains($email, 'decline')) {
            return PaymentResponse::failed('PayPal checkout failed: Customer cancelled or declined transaction.', [
                'gateway' => $this->getName(),
                'error_code' => 'paypal_cancelled',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        $transactionId = 'pay_' . bin2hex(random_bytes(10));

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
        Log::info('Refunding PayPal payment', [
            'transaction_id' => $paymentGatewayId,
            'amount' => $amount,
        ]);

        $refundId = 'pay_ref_' . bin2hex(random_bytes(10));

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
        return 'paypal';
    }
}
