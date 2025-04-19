<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DraftTransactionUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:1',
            'direction' => 'required|in:debit,credit',
            'category_id' => 'nullable|exists:categories,id',
            'month_year_id' => 'required|exists:month_years,id',
            'date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
