<?php

namespace App\Services\Payment;

use App\Repositories\Interfaces\PaymentGatewayRepositoryInterface;
use App\Services\Payment\Exceptions\UnsupportedPaymentGatewayException;
use App\Services\Payment\Gateways\StripePaymentGateway;
use App\Services\Payment\Interfaces\PaymentGatewayInterface;
use InvalidArgumentException;

class PaymentGatewayManager
{
    /**
     * Cache resolved gateway instances.
     */
    protected array $resolvedGateways = [];

    public function __construct(
        protected PaymentGatewayRepositoryInterface $gatewayRepository
    ) {}

    /**
     * Get a payment gateway instance by its code.
     *
     * @throws UnsupportedPaymentGatewayException
     */
    public function gateway(?string $code = null): PaymentGatewayInterface
    {
        // If no code provided, default to the first active gateway
        if (is_null($code)) {
            $activeGateways = $this->gatewayRepository->getActiveGateways();
            if ($activeGateways->isEmpty()) {
                throw new UnsupportedPaymentGatewayException('default');
            }
            $code = $activeGateways->first()->code;
        }

        if (isset($this->resolvedGateways[$code])) {
            return $this->resolvedGateways[$code];
        }

        return $this->resolvedGateways[$code] = $this->resolveGateway($code);
    }

    /**
     * Resolve the gateway instance using the database configuration.
     *
     * @throws UnsupportedPaymentGatewayException
     */
    protected function resolveGateway(string $code): PaymentGatewayInterface
    {
        $gatewayModel = $this->gatewayRepository->findByCode($code);

        if (!$gatewayModel || !$gatewayModel->is_active) {
            throw new UnsupportedPaymentGatewayException($code);
        }

        $config = $gatewayModel->config ?? [];

        return match ($code) {
            'stripe' => new StripePaymentGateway($config),
            // We can add other gateways here like 'paypal' => new PaypalPaymentGateway($config),
            default  => throw new UnsupportedPaymentGatewayException($code),
        };
    }
}
