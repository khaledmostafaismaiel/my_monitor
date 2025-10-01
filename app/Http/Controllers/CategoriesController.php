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
        $categories = Category::where('family_id', auth()->user()->family_id)
            ->when(null !== \request("name"), function($query){
                $query->where("name", "LIKE", "%".\request("name")."%");
            })
            ->when(null !== \request("status"), function($query){
                $query->where("status", \request("status"));
            })
            ->orderBy("name")
            ->paginate(10);

        return view('categories' ,compact('categories'));
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
        if($category->delete()){
            session()->flash('message','Category deleted successfully');
        }else{
            session()->flash('message',"Category didn't deleted successfully");
        }
        return redirect('/categories?page_number=1');
    }
}
