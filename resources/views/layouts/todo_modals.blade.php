{{-- Recursive modal generator for todos --}}
@include('layouts/edit_todo', ['todo' => $todo, 'modalId' => "editTodo{$todo->id}", 'all_month_years' => $all_month_years, 'allTodos' => $allTodos])
@include('layouts/delete_todo', ['todo' => $todo, 'modalId' => "deleteTodo{$todo->id}"])
@include('layouts/add_todo', ['modalId' => "addChildTodo{$todo->id}", 'parentId' => $todo->id, 'all_month_years' => $all_month_years, 'allTodos' => $allTodos])

@foreach($todo->children as $child)
    @include('layouts/todo_modals', ['todo' => $child, 'all_month_years' => $all_month_years, 'allTodos' => $allTodos])
@endforeach
