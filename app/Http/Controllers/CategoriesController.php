<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryDestroyRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Inertia\Inertia;

class CategoriesController extends Controller
{
    public function index()
    {
        $allCategories = Category::where('family_id', auth()->user()->family_id)
            ->orderBy('name')
            ->get(['id', 'name', 'status', 'limit', 'parent_id', 'family_id']);

        return Inertia::render('Categories/Index', [
            'categories' => $allCategories,
        ]);
    }

    public function store(CategoryStoreRequest $request)
    {
        Category::create(array_merge(
            $request->validated(),
            ['family_id' => auth()->user()->family_id],
        ));

        return back()->with('message', 'Category created.');
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->validated());
        return back()->with('message', 'Category updated.');
    }

    public function destroy(CategoryDestroyRequest $request, Category $category)
    {
        $category->load('children');

        if ($category->children && $category->children->count() > 0) {
            return back()->with('error', 'Cannot delete a category that has subcategories. Reassign or delete them first.');
        }

        if ($category->transactions()->exists()) {
            return back()->with('error', 'Cannot delete a category that has transactions linked to it.');
        }

        $category->delete();

        return back()->with('message', 'Category deleted.');
    }
}
