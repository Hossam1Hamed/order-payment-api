<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Dto\PaymentResponse;
use Illuminate\Support\Facades\Log;

class StripeGateway implements PaymentGatewayInterface
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
        Log::info('Processing Stripe charge', [
            'amount' => $amount,
            'config' => $this->config,
        ]);

        $token = $data['stripe_token'] ?? '';
        if ($token === 'tok_chargeDeclined' || $token === 'fail') {
            return PaymentResponse::failed('Stripe charge declined: Invalid token or blocked charge.', [
                'gateway' => $this->getName(),
                'error_code' => 'charge_declined',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        $transactionId = 'ch_' . bin2hex(random_bytes(10));

        return PaymentResponse::success($transactionId, [
            'gateway' => $this->getName(),
            'charge_id' => $transactionId,
            'receipt_url' => 'https://stripe.com/receipt/' . $transactionId,
            'amount' => $amount,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Refund a specific transaction amount.
     */
    public function refund(string $paymentGatewayId, float $amount): PaymentResponse
    {
        Log::info('Refunding Stripe charge', [
            'charge_id' => $paymentGatewayId,
            'amount' => $amount,
        ]);

        $refundId = 're_' . bin2hex(random_bytes(10));

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
        return 'stripe';
    }
}
