<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoDestroyRequest;
use App\Http\Requests\TodoStoreRequest;
use App\Http\Requests\TodoUpdateRequest;
use App\Models\Todo;

class TodosController extends Controller
{
    public function index()
    {
        $todos = Todo::visible(auth()->id(), auth()->user()->family_id)
            ->roots()
            ->with('allChildren', 'user')
            ->when(request("title") != "", function ($query) {
                $query->where("title", "LIKE", "%" . request("title") . "%");
            })
            ->when(request("status") != "", function ($query) {
                $query->where("status", request("status"));
            })
            ->when(request("priority") != "", function ($query) {
                $query->where("priority", request("priority"));
            })
            ->when(request("scope") != "", function ($query) {
                $query->where("scope", request("scope"));
            })
            ->when(request("month_year_id") != "", function ($query) {
                $query->where("month_year_id", request("month_year_id"));
            })
            ->orderBy("priority", "desc")
            ->orderBy("order")
            ->orderBy("created_at", "desc")
            ->paginate(10);

        $all_month_years = auth()->user()->family->monthYears()
            ->orderBy('id', 'desc')
            ->get();

        $allTodos = Todo::visible(auth()->id(), auth()->user()->family_id)
            ->orderBy('title')
            ->get();

        return view('todos', compact('todos', 'all_month_years', 'allTodos'));
    }

    public function store(TodoStoreRequest $request)
    {
        Todo::create(
            array_merge(
                $request->validated(),
                [
                    'family_id' => auth()->user()->family_id,
                    'user_id' => auth()->id(),
                ]
            )
        );

        session()->flash('message', 'Todo created successfully');
        return redirect('/todos');
    }

    public function update(TodoUpdateRequest $request, $id)
    {
        $todo = Todo::findOrFail($id);

        if ($todo->update($request->validated())) {
            session()->flash('message', 'Todo updated successfully');
            return redirect('/todos');
        } else {
            session()->flash('message', 'Todo was not updated successfully');
            return redirect('/todos');
        }
    }

    public function destroy(TodoDestroyRequest $request, Todo $todo)
    {
        if ($todo->delete()) {
            session()->flash('message', 'Todo deleted successfully');
        } else {
            session()->flash('message', "Todo was not deleted successfully");
        }
        return redirect('/todos');
    }

    public function toggleStatus(Todo $todo)
    {
        // Check visibility
        if ($todo->family_id !== auth()->user()->family_id) {
            abort(403);
        }

        if ($todo->scope === 'private' && $todo->user_id !== auth()->id()) {
            abort(403);
        }

        $statuses = ['pending', 'in_progress', 'completed'];
        $currentIndex = array_search($todo->status, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);

        $todo->update(['status' => $statuses[$nextIndex]]);

        session()->flash('message', 'Todo status updated');
        return redirect('/todos');
    }

    public function reorder()
    {
        $orders = request('orders', []);
        $parentId = request('parent_id');
        $todoId = request('todo_id');

        // If a specific todo was moved to a new parent, update its parent_id
        if ($todoId) {
            $todo = Todo::find($todoId);
            if ($todo && $todo->family_id === auth()->user()->family_id) {
                $newParentId = $parentId ?: null;

                // Validate the new parent if set
                if ($newParentId) {
                    $parent = Todo::find($newParentId);
                    if (!$parent || $parent->family_id !== auth()->user()->family_id) {
                        return response()->json(['success' => false, 'error' => 'Invalid parent']);
                    }

                    // Check for circular reference
                    if ($parent->id == $todo->id) {
                        return response()->json(['success' => false, 'error' => 'Cannot be own parent']);
                    }

                    // Check if new parent is a descendant of this todo
                    $descendantIds = $todo->descendants()->pluck('id')->toArray();
                    if (in_array($newParentId, $descendantIds)) {
                        return response()->json(['success' => false, 'error' => 'Cannot move to descendant']);
                    }

                    // Public todos can only be under public parents
                    if ($todo->scope === 'public' && $parent->scope === 'private') {
                        return response()->json(['success' => false, 'error' => 'Public todos can only be under public parents']);
                    }
                }

                $todo->update(['parent_id' => $newParentId]);
            }
        }

        // Update order for all items
        foreach ($orders as $id => $order) {
            $todo = Todo::find($id);
            if ($todo && $todo->family_id === auth()->user()->family_id) {
                $todo->update(['order' => (int)$order]);
            }
        }

        return response()->json(['success' => true]);
    }
}
