<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;



class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expense::all() ;

        return view('expenses' ,compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add_expense');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        $expense = Expense::findOrfail($id);
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

        $expense->save();

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

    public function delete($id)
    {
        Expense::findOrfail($id)->delete();

        return redirect('/expenses');
    }

}
