<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateProductRequest extends FormRequest
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
            'name'                   => ['required', 'string', 'max:255'],
            'SKU'                    => ['required', 'string', 'max:255', 'unique:products'],
            'price'                  => ['required', 'numeric', 'min:1'],
            'initial_stock_quantity' => ['required', 'integer', 'min:0'],
            'category_id'            => ['nullable', 'exists:categories,id'],
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
            'name.required'                   => 'Product name is required.',
            'name.string'                     => 'Product name must be a string.',
            'name.max'                        => 'Product name must not exceed 255 characters.',
            'SKU.required'                    => 'SKU is required.',
            'SKU.string'                      => 'SKU must be a string.',
            'SKU.max'                         => 'SKU must not exceed 255 characters.',
            'SKU.unique'                      => 'The provided SKU already exists.',
            'price.required'                  => 'Price is required.',
            'price.numeric'                   => 'Price must be a number.',
            'price.min'                       => 'Price must be at least 1.',
            'initial_stock_quantity.required' => 'Initial stock quantity is required.',
            'initial_stock_quantity.integer'  => 'Initial stock quantity must be an integer.',
            'initial_stock_quantity.min'      => 'Initial stock quantity must be at least 0.',
            'category_id.exists'              => 'The selected category does not exist.',
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
