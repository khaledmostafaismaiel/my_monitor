<?php

namespace App\Http\Requests;

use App\Models\Todo;
use Illuminate\Foundation\Http\FormRequest;

class TodoDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $todo = $this->route('todo');

        if (!$todo) {
            return false;
        }

        // Must be in same family
        if ($todo->family_id !== auth()->user()->family_id) {
            return false;
        }

        // If private, must be owner
        if ($todo->scope === 'private' && $todo->user_id !== auth()->id()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
