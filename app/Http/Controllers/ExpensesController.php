<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use App\User ;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $expenses = Expense::all() ;
        $expenses = User::first()->expenses ;
        return view('expenses' ,compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category_set = Category::all();

        return view('add_expense',compact('category_set'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $valid = request()->validate(
            [
           'expense_name'=> ['required'] ,
            'price'=> ['required' ] ,
            'category'=> ['required' ] ,
//            'password'=> ['required' , 'confirmed'] ,

           ]
        );
        Expense::create(request([
            'user_id'=> '1' ,
            'expense_name'=> request('expense_name') ,
            'price'=> request('price') ,
            'category'=> request('category') ,
            'comment'=> request('comment') ,
            'created_at'=> request('created_at')
        ]));

//        Expense::create([
//           'user_id'=> '1' ,
//           'expense_name'=> request('expense_name') ,
//            'price'=> request('price') ,
//            'category'=> request('category') ,
//            'comment'=> request('comment') ,
//            'created_at'=> request('created_at')
//        ]);
//        Expense::create(request([
//            'user_id',
//            'expense_name' ,
//            'price' ,
//            'category' ,
//            'comment' ,
//            'created_at'
//        ]));
        //Expense::create(request()->all())
        return redirect('/expenses/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(/*$id*/ Expense $expense)
    {

//        $expense = Expense::findOrfail($id);
        $category_set = Category::all();
        return view('edit_expense',compact('expense','category_set'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::findOrfail($id);

        $expense->expense_name = request('expense_name') ;
        $expense->price = request('price') ;
        $expense->category = request('category') ;
        $expense->comment = request('comment')  ;
        $expense->created_at = request('created_at')  ;
        $expense->updated_at = date("Y-m-d h:i:s");

        $expense->update();

        return redirect('/expenses');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function delete(/*$id*/Expense $expense)
    {
        $expense->delete();
//        Expense::findOrfail($id)->delete();
        return redirect('/expenses');
    }

    public function search()
    {
        $search_for = request('search') ;
        return redirect('/expenses');

    }
}
