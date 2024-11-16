<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreatePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supplier_id'        => ['required', 'exists:suppliers,id'],
            'total_amount'       => ['required', 'numeric', 'min:0'],
            'purchase_date'      => ['required', 'date', 'before_or_equal:today'],
            'items'              => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'supplier_id.required'          => 'Supplier ID is required.',
            'supplier_id.exists'            => 'The selected supplier does not exist.',
            'total_amount.required'         => 'Total amount is required.',
            'total_amount.numeric'          => 'Total amount must be a valid number.',
            'total_amount.min'              => 'Total amount must be at least 0.',
            'purchase_date.required'        => 'Purchase date is required.',
            'purchase_date.date'            => 'Purchase date must be a valid date.',
            'purchase_date.before_or_equal' => 'Purchase date cannot be in the future.',
            'items.required'                => 'Purchase items are required.',

            'items.*.product_id.required' => 'Product ID is required for each item.',
            'items.*.product_id.exists'   => 'The selected product does not exist in the database.',
            'items.*.quantity.required'   => 'Quantity is required for each item.',
            'items.*.quantity.integer'    => 'Quantity must be a valid integer.',
            'items.*.quantity.min'        => 'Quantity must be at least 1.',
            'items.*.unit_price.required' => 'Unit price is required for each item.',
            'items.*.unit_price.numeric'  => 'Unit price must be a valid number.',
            'items.*.unit_price.min'      => 'Unit price must be at least 0.'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->getMessages();

        $response = [
            'status'  => 422,
            'message' => 'Validation failed',
            'errors'  => array_map(function ($messages) {
                return [
                    'message' => $messages[0],
                ];
            }, $errors),
        ];

        // throw new HttpResponseException(response()->json($response, 422));
        return response()->json($response, 422);
    }
}
