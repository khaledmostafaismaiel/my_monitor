<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DraftTransactionUpdateAndAddRequest extends FormRequest
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
            'id' => 'required|exists:transactions,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:1',
            'direction' => 'required|in:debit,credit',
            'category_id' => 'required|exists:categories,id',
            'month_year_id' => 'required|exists:month_years,id',
            'date' => 'required|date|before_or_equal:today',
            'comment' => 'nullable|string|max:500',
        ];
    }
}
