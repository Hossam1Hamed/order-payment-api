<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Exceptions\UnsupportedPaymentGatewayException;
use App\Services\Payment\Gateways\CreditCardGateway;
use App\Services\Payment\Gateways\PayPalGateway;
use App\Services\Payment\Gateways\StripeGateway;

class PaymentGatewayManager
{
    /**
     * Cache resolved gateway instances.
     */
    protected array $resolvedGateways = [];

    /**
     * Get a payment gateway instance by its method/driver name.
     *
     * @throws UnsupportedPaymentGatewayException
     */
    public function gateway(string $method): PaymentGatewayInterface
    {
        if (isset($this->resolvedGateways[$method])) {
            return $this->resolvedGateways[$method];
        }

        return $this->resolvedGateways[$method] = $this->resolveGateway($method);
    }

    /**
     * Resolve the gateway instance using the environment/config file values.
     *
     * @throws UnsupportedPaymentGatewayException
     */
    protected function resolveGateway(string $method): PaymentGatewayInterface
    {
        return match ($method) {
            'credit_card' => new CreditCardGateway(config('services.credit_card', [])),
            'paypal'      => new PayPalGateway(config('services.paypal', [])),
            'stripe'      => new StripeGateway(config('services.stripe', [])),
            default       => throw new UnsupportedPaymentGatewayException($method),
        };
    }
}
