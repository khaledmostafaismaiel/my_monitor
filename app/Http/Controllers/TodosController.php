<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoDestroyRequest;
use App\Http\Requests\TodoStoreRequest;
use App\Http\Requests\TodoUpdateRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodosController extends Controller
{
    public function index(Request $request)
    {
        $family = auth()->user()->family;

        $todos = Todo::visible(auth()->id(), $family->id)
            ->roots()
            ->with('allChildren', 'user')
            ->when($request->filled('title'), fn ($q) => $q->where('title', 'LIKE', '%' . $request->title . '%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->priority))
            ->when($request->filled('scope'), fn ($q) => $q->where('scope', $request->scope))
            ->when($request->filled('month_year_id'), fn ($q) => $q->where('month_year_id', $request->month_year_id))
            ->orderByDesc('priority')
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Todos/Index', [
            'todos' => $todos,
            'filters' => $request->only(['title', 'status', 'priority', 'scope', 'month_year_id']),
            'options' => [
                'month_years' => $family->monthYears()->orderByDesc('id')->get(['id', 'month', 'year']),
                'allTodos' => Todo::visible(auth()->id(), $family->id)->orderBy('title')->get(['id', 'title', 'parent_id']),
            ],
        ]);
    }

    public function store(TodoStoreRequest $request)
    {
        Todo::create(array_merge(
            $request->validated(),
            ['family_id' => auth()->user()->family_id, 'user_id' => auth()->id()],
        ));

        return back()->with('message', 'Todo created.');
    }

    public function update(TodoUpdateRequest $request, Todo $todo)
    {
        $todo->update($request->validated());
        return back()->with('message', 'Todo updated.');
    }

    public function destroy(TodoDestroyRequest $request, Todo $todo)
    {
        $todo->delete();
        return back()->with('message', 'Todo deleted.');
    }

    public function toggleStatus(Request $request, Todo $todo)
    {
        if ($todo->family_id !== auth()->user()->family_id) {
            abort(403);
        }
        if ($todo->scope === 'private' && $todo->user_id !== auth()->id()) {
            abort(403);
        }

        $statuses = ['pending', 'in_progress', 'completed'];
        $explicit = $request->input('status');

        if (in_array($explicit, $statuses, true)) {
            $next = $explicit;
        } else {
            $next = $statuses[(array_search($todo->status, $statuses) + 1) % count($statuses)];
        }

        $todo->update(['status' => $next]);

        return back()->with('message', 'Status updated.');
    }

    public function reorder(Request $request)
    {
        $orders = $request->input('orders', []);
        $parentId = $request->input('parent_id');
        $todoId = $request->input('todo_id');

        if ($todoId) {
            $todo = Todo::find($todoId);
            if ($todo && $todo->family_id === auth()->user()->family_id) {
                $newParentId = $parentId ?: null;
                if ($newParentId) {
                    $parent = Todo::find($newParentId);
                    if (!$parent || $parent->family_id !== auth()->user()->family_id) {
                        return response()->json(['success' => false, 'error' => 'Invalid parent']);
                    }
                    if ($parent->id == $todo->id) {
                        return response()->json(['success' => false, 'error' => 'Cannot be own parent']);
                    }
                    $descendantIds = $todo->descendants()->pluck('id')->toArray();
                    if (in_array($newParentId, $descendantIds)) {
                        return response()->json(['success' => false, 'error' => 'Cannot move to descendant']);
                    }
                    if ($todo->scope === 'public' && $parent->scope === 'private') {
                        return response()->json(['success' => false, 'error' => 'Public todos can only be under public parents']);
                    }
                }
                $todo->update(['parent_id' => $newParentId]);
            }
        }

        foreach ($orders as $id => $order) {
            $todo = Todo::find($id);
            if ($todo && $todo->family_id === auth()->user()->family_id) {
                $todo->update(['order' => (int) $order]);
            }
        }

        return response()->json(['success' => true]);
    }
}
