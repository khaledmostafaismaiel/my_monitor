<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::when(null !== \request("search"), function($query){
                $query->where("name", "LIKE", "%".\request("search")."%");
            })
            ->paginate(10);

        return view('categories' ,compact('categories'));
    }

    public function create()
    {
        return view('add_category');
    }

    public function store(Request $request)
    {
        if(Category::create([
            'name'=> request('name'),
        ])){
            return redirect('/categories');
        }else{
            return redirect('/categories/create');
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $category_set = Category::all();
        return view('edit_category',compact('category'));
    }

    public function update(Request $request, $id)
    {

        $category = Category::findOrFail($id);
        $category->name = strtolower(trim(request('name'))) ;

        if($category->update()){
            session()->flash('message','Category updated successfully');
            return redirect('/categories?page_number=1');

        }else{
            session()->flash('message','Category didn\'t updated successfully');
            return redirect('/categories/'.$id."/edit");
        }
    }
}
