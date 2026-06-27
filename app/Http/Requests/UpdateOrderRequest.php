<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
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
            'status' => [
                'sometimes',
                'required',
                Rule::in([
                    Order::STATUS_PENDING,
                    Order::STATUS_CONFIRMED,
                    Order::STATUS_CANCELLED,
                ]),
            ],
            'items'                  => 'sometimes|array|min:1',
            'items.*.product_name'   => 'required_with:items|string|max:255',
            'items.*.quantity'       => 'required_with:items|integer|min:1',
            'items.*.price'          => 'required_with:items|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'status.in'                    => 'Status must be one of: pending, confirmed, cancelled.',
            'items.*.product_name.required_with' => 'Each item must have a product name.',
            'items.*.quantity.required_with'     => 'Each item must have a quantity.',
            'items.*.quantity.min'               => 'Quantity must be at least 1.',
            'items.*.price.required_with'        => 'Each item must have a price.',
            'items.*.price.min'                  => 'Price cannot be negative.',
        ];
    }
}
