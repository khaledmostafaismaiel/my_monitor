<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NormalTransactionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'price' => ['required', 'numeric', 'gt:0'],
            'quantity' => ['required', 'numeric', 'gte:1'],
            'direction' => ['required', 'in:credit,debit'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'month_year_id' => ['required', 'integer', 'exists:month_years,id'],
            'date' => ['required', 'date'],
            'comment' => ['nullable', 'string', 'max:500'],
            'wallet_id' => 'required|exists:wallets,id',
        ];
    }
}
