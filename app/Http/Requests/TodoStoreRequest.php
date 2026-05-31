<?php

namespace App\Http\Requests;

use App\Models\MonthYear;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class TodoStoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|in:pending,in_progress,completed',
            'scope' => 'required|in:public,private',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'parent_id' => 'nullable|exists:todos,id',
            'month_year_id' => 'nullable|exists:month_years,id',
            'order' => 'nullable|integer|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate due_date is within month_year interval if both are provided
            if ($this->month_year_id && $this->due_date) {
                $monthYear = MonthYear::find($this->month_year_id);
                if ($monthYear) {
                    $start = Carbon::create($monthYear->year, $monthYear->month, 1)->startOfMonth();
                    $end = $start->copy()->endOfMonth();
                    $dueDate = Carbon::parse($this->due_date);

                    if (!$dueDate->between($start, $end)) {
                        $validator->errors()->add(
                            'due_date',
                            "Due date must be within {$monthYear->month}/{$monthYear->year}"
                        );
                    }
                }
            }

            // Validate parent_id belongs to same family and is visible
            if ($this->parent_id) {
                $parent = \App\Models\Todo::find($this->parent_id);
                if ($parent) {
                    if ($parent->family_id !== auth()->user()->family_id) {
                        $validator->errors()->add('parent_id', 'Invalid parent todo');
                    }
                    if ($parent->scope === 'private' && $parent->user_id !== auth()->id()) {
                        $validator->errors()->add('parent_id', 'Cannot add child to a private todo you do not own');
                    }
                    // Public children can only be added to public parents
                    if ($this->scope === 'public' && $parent->scope === 'private') {
                        $validator->errors()->add('scope', 'Public todos can only be added to public parents');
                    }
                }
            }
        });
    }
}
