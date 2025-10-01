<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CategoryDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $category = $this->route('category');

        return $category && $category->family_id == auth()->user()->family_id;

    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $category = $this->route('category');

            if ($category && $category->transactions()->exists()) {
                $validator->errors()->add('category', "Category can't be deleted because it has associated transactions.");
            }
        });
    }
}
