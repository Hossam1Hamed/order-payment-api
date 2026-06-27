<?php

namespace App\Services\Payment\Gateways;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Payment\Dto\PaymentResponse;
use App\Services\Payment\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;

class StripePaymentGateway implements PaymentGatewayInterface
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Process a payment for a specific order.
     */
    public function process(Order $order, array $paymentDetails): PaymentResponse
    {
        Log::info('Processing Stripe payment', [
            'order_id' => $order->id,
            'total'    => $order->total,
            'config'   => $this->config,
        ]);

        // Simulating stripe payment processing.
        // We can simulate failure if the card number ends with '0000'
        $cardNumber = $paymentDetails['card_number'] ?? '';
        if (str_ends_with($cardNumber, '0000')) {
            return PaymentResponse::failed('Stripe charge declined: Insufficient funds.', [
                'gateway' => 'stripe',
                'error_code' => 'card_declined',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        $transactionId = 'ch_' . bin2hex(random_bytes(10));

        return PaymentResponse::success($transactionId, [
            'gateway' => 'stripe',
            'charge_id' => $transactionId,
            'receipt_url' => 'https://stripe.com/receipt/' . $transactionId,
            'amount_paid' => $order->total,
            'currency' => 'usd',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Refund a processed payment.
     */
    public function refund(Payment $payment, float $amount): PaymentResponse
    {
        Log::info('Refunding Stripe payment', [
            'payment_gateway_id' => $payment->id,
            'amount'     => $amount,
        ]);

        $refundId = 're_' . bin2hex(random_bytes(10));

        return PaymentResponse::success($refundId, [
            'gateway' => 'stripe',
            'refund_id' => $refundId,
            'amount_refunded' => $amount,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
