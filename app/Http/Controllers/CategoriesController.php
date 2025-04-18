<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

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

    public function store(Request $request)
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

    public function update(Request $request, $id)
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
