<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanStoreRequest extends FormRequest
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
            'name' => 'required',
            'amount' => 'required|numeric',
            'ves_exchange' => 'required|numeric',
            'currency' => 'required|string',
			'description' => 'nullable|string',
			'estimated_refund_date' => 'nullable|string|date',
			'rate' => 'nullable|numeric',
        ];
    }
}
