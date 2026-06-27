<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'items'                  => 'required|array|min:1',
            'items.*.product_name'   => 'required|string|max:255',
            'items.*.quantity'       => 'required|integer|min:1',
            'items.*.price'          => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'               => 'At least one order item is required.',
            'items.*.product_name.required' => 'Each item must have a product name.',
            'items.*.quantity.required'    => 'Each item must have a quantity.',
            'items.*.quantity.min'         => 'Quantity must be at least 1.',
            'items.*.price.required'       => 'Each item must have a price.',
            'items.*.price.min'            => 'Price cannot be negative.',
        ];
    }
}
