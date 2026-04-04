<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryDestroyRequest;
use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoriesController extends Controller
{
    public function index()
    {
        $allCategories = Category::where('family_id', auth()->user()->family_id)
            ->with('children')
            ->orderBy("name")
            ->get();

        $categoryTree = $this->buildTree($allCategories);
        $rootCategories = $categoryTree->slice(0, 10);
        $hasMore = $categoryTree->count() > 10;

        return view('categories' ,compact('rootCategories', 'allCategories', 'hasMore'));
    }

    private function buildTree($categories)
    {
        $grouped = $categories->groupBy('parent_id');
        $rootCategories = $grouped->get(null) ?? collect();

        $mainCategory = $rootCategories->firstWhere('name', 'Main');
        
        if ($mainCategory) {
            $mainChildren = $grouped->get($mainCategory->id) ?? collect();
            
            if ($mainChildren->isNotEmpty()) {
                $result = collect([$this->addChildren($mainCategory, $grouped)]);
                
                $otherRoots = $rootCategories->filter(fn($c) => $c->name !== 'Main');
                return $result->concat($otherRoots->map(function ($category) use ($grouped) {
                    return $this->addChildren($category, $grouped);
                }));
            }
        }

        return $rootCategories->map(function ($category) use ($grouped) {
            return $this->addChildren($category, $grouped);
        });
    }

    public function create()
    {
        return redirect('/categories');
    }

    private function addChildren($category, $grouped)
    {
        $children = $grouped->get($category->id) ?? collect();
        $category->children = $children->map(function ($child) use ($grouped) {
            return $this->addChildren($child, $grouped);
        });
        return $category;
    }

    public function store(CategoryStoreRequest $request)
    {
        Category::create(
            array_merge(
                $request->toArray(),
                [
                    'family_id'=> auth()->user()->family_id,
                ]
            )
        );

        return redirect('/categories');
    }

    public function update(CategoryUpdateRequest $request, $id)
    {

        $category = Category::findOrFail($id);

        if($category->update($request->toArray())){
            session()->flash('message','Category updated successfully');
            return redirect('/categories?page_number=1');

        }else{
            session()->flash('message','Category didn\'t updated successfully');
            return redirect('/categories/'.$id."/edit");
        }
    }

    public function destroy(CategoryDestroyRequest $request, Category $category)
    {
        $category->load('children');
        
        if ($category->children && $category->children->count() > 0) {
            session()->flash('message', 'Cannot delete category with children. Please delete or reassign child categories first.');
            return redirect('/categories?page_number=1');
        }
        
        if($category->delete()){
            session()->flash('message','Category deleted successfully');
        }else{
            session()->flash('message',"Category didn't deleted successfully");
        }
        return redirect('/categories?page_number=1');
    }
}
