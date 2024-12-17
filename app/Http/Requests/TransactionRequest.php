<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'transaction_type' => 'required|string|In:buy,rent',
            'details' => 'required|string|max:500',
            'required_documents' => 'required|max:250',
            'cost' => 'required|max:50',
            'city_id' => 'required|exists:cities,id',
            'contact_method_id' => 'required|exists:contact_methods,id',
        ];
    }
}
