<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'method' => [
                'required',
                'string',
                Rule::in([
                    Payment::METHOD_CREDIT_CARD,
                    Payment::METHOD_PAYPAL,
                    Payment::METHOD_STRIPE,
                    Payment::METHOD_BANK_TRANSFER,
                ]),
            ],
            // Standard client-side pre-authorized payment reference or payload token
            'payment_gateway_id' => 'sometimes|string|max:255',
            
            // Conditional validation rules based on selected gateway method:
            'card_number'        => 'required_if:method,' . Payment::METHOD_CREDIT_CARD . '|string',
            'cvv'                => 'required_if:method,' . Payment::METHOD_CREDIT_CARD . '|string|min:3|max:4',
            'paypal_email'       => 'required_if:method,' . Payment::METHOD_PAYPAL . '|email',
            'stripe_token'       => 'required_if:method,' . Payment::METHOD_STRIPE . '|string',
        ];
    }

    public function messages(): array
    {
        return [
            'method.required' => 'The payment method is required.',
            'method.in'       => 'Supported payment methods are: credit_card, paypal, stripe, bank_transfer.',
        ];
    }
}
