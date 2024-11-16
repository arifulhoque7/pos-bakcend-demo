<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreatePurchaseItemRequest extends FormRequest
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
            'purchase_id' => ['required', 'exists:purchases,id'],
            'product_id'  => ['required', 'exists:products,id'],
            'quantity'    => ['required', 'integer', 'min:1'],
            'unit_price'  => ['required', 'numeric', 'min:0'],
            'total_price' => ['required', 'numeric', 'min:0'],
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
            'purchase_id.required' => 'Purchase ID is required.',
            'purchase_id.exists'   => 'The selected purchase does not exist.',
            'product_id.required'  => 'Product ID is required.',
            'product_id.exists'    => 'The selected product does not exist.',
            'quantity.required'    => 'Quantity is required.',
            'quantity.integer'     => 'Quantity must be an integer.',
            'quantity.min'         => 'Quantity must be at least 1.',
            'unit_price.required'  => 'Unit price is required.',
            'unit_price.numeric'   => 'Unit price must be a valid number.',
            'unit_price.min'       => 'Unit price must be at least 0.',
            'total_price.required' => 'Total price is required.',
            'total_price.numeric'  => 'Total price must be a valid number.',
            'total_price.min'      => 'Total price must be at least 0.',
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
