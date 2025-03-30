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
            ->orderBy("name")
            ->paginate(10);

        return view('categories' ,compact('categories'));
    }

    public function store(Request $request)
    {
        Category::create(
            [
                'name'=> request('name'),
            ]
        );

        return redirect('/categories');
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

    public function destroy($id)
    {
        if(Category::findOrFail($id)->delete()){
            session()->flash('message','Category deleted successfully');
        }else{
            session()->flash('message',"Category didn't deleted successfully");
        }
        return redirect('/categories?page_number=1');
    }
}
