<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentGateway::updateOrCreate(
            ['code' => PaymentGateway::CODE_STRIPE],
            [
                'name' => 'Stripe Gateway',
                'is_active' => true,
                'config' => [
                    'secret_key' => 'sk_test_51MockKeyStripeHere...',
                    'public_key' => 'pk_test_51MockKeyStripeHere...',
                    'webhook_secret' => 'whsec_MockSecret...',
                ],
            ]
        );

        PaymentGateway::updateOrCreate(
            ['code' => PaymentGateway::CODE_PAYPAL],
            [
                'name' => 'PayPal Gateway',
                'is_active' => false, // inactive by default in this demo
                'config' => [
                    'client_id' => 'mock_paypal_client_id...',
                    'client_secret' => 'mock_paypal_client_secret...',
                    'mode' => 'sandbox',
                ],
            ]
        );
    }
}
