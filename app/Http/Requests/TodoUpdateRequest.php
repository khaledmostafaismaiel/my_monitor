<?php

namespace App\Http\Requests;

use App\Models\MonthYear;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class TodoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $todo = $this->route('todo');

        if (!$todo instanceof Todo) {
            return false;
        }

        $user = auth()->user();

        if (!$user) {
            return false;
        }

        if ($todo->family_id !== $user->family_id) {
            return false;
        }

        if ($todo->scope === 'private' && $todo->user_id !== $user->id) {
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
            $todo = $this->route('todo');
            $user = $this->user();

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

            // Validate parent_id is not self or descendant
            if ($this->parent_id) {
                if ($this->parent_id == $todo->id) {
                    $validator->errors()->add('parent_id', 'A todo cannot be its own parent');
                }

                $descendantIds = $todo->descendants()->pluck('id')->toArray();
                if (in_array($this->parent_id, $descendantIds)) {
                    $validator->errors()->add('parent_id', 'Cannot set a descendant as parent');
                }

                $parent = Todo::find($this->parent_id);
                if ($parent) {
                    if ($parent->family_id !== $user?->family_id) {
                        $validator->errors()->add('parent_id', 'Invalid parent todo');
                    }
                    if ($parent->scope === 'private' && $parent->user_id !== $user?->id) {
                        $validator->errors()->add('parent_id', 'Cannot move to a private todo you do not own');
                    }
                    // Public children can only be added to public parents
                    if ($this->scope === 'public' && $parent->scope === 'private') {
                        $validator->errors()->add('scope', 'Public todos can only be under public parents');
                    }
                }
            }

            // If changing to public, check if current parent allows it
            if ($todo && $todo->parent_id && !$this->parent_id) {
                $existingParent = $todo->parent;
                if ($existingParent && $this->scope === 'public' && $existingParent->scope === 'private') {
                    $validator->errors()->add('scope', 'Cannot make todo public when parent is private');
                }
            }

            // If changing scope to private, children should also be private
            if ($this->scope === 'private' && $todo) {
                $publicChildren = $todo->children()->where('scope', 'public')->exists();
                if ($publicChildren) {
                    $validator->errors()->add('scope', 'Cannot make private: this todo has public children. Make children private first.');
                }
            }
        });
    }
}
