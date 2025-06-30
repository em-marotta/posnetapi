<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreCardRequest extends FormRequest
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
            'dni' => 'required|string|exists:clients,dni',
            'card_type' => 'required|in:VISA,AMEX',
            'bank_name' => 'required|string|max:255',
            'card_number' => 'required|digits:8|unique:cards,card_number',
            'available_limit' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'dni.required' => 'The DNI field is required.',
            'dni.string' => 'The DNI must be a string.',
            'dni.exists' => 'The specified DNI does not match any registered client.',

            'card_type.required' => 'The card type is required.',
            'card_type.in' => 'Only VISA or AMEX cards are accepted.',

            'bank_name.required' => 'The bank name is required.',
            'bank_name.string' => 'The bank name must be a string.',
            'bank_name.max' => 'The bank name must not exceed 255 characters.',

            'card_number.required' => 'The card number is required.',
            'card_number.digits' => 'The card number must be exactly 8 digits.',
            'card_number.unique' => 'This card number is already registered.',

            'available_limit.required' => 'The available limit is required.',
            'available_limit.numeric' => 'The available limit must be a number.',
            'available_limit.min' => 'The available limit must be at least 0.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }

}
