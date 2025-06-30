<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StorePaymentRequest extends FormRequest
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
            'card_number' => 'required|digits:8|exists:cards,card_number',
            'amount' => 'required|numeric|min:0.01',
            'installments' => 'required|integer|min:1|max:6',
        ];
    }

    public function messages(): array
    {
        return [
            'card_number.required' => 'The card number is required.',
            'card_number.digits' => 'The card number must be exactly 8 digits.',
            'card_number.exists' => 'The card number does not exist in our records.',

            'amount.required' => 'The payment amount is required.',
            'amount.numeric' => 'The amount must be a numeric value.',
            'amount.min' => 'The amount must be at least 0.01.',

            'installments.required' => 'The number of installments is required.',
            'installments.integer' => 'The number of installments must be an integer.',
            'installments.min' => 'You must choose at least 1 installment.',
            'installments.max' => 'You can select up to 6 installments only.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
