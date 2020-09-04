<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Category;

use App\Mail\ExpenseAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use App\User ;
use Illuminate\Support\Facades\Mail;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $expenses = auth()->user()->expenses ;
        $expenses = auth()->user()->expenses()->paginate(5) ;
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
        $attributes =  $this->validateExpense();

        if(Expense::create([
            'user_id'=> auth()->id() ,
            'expense_name'=> sql_sanitize(strtolower(trim(request('expense_name')))) ,
            'price'=> sql_sanitize(trim(request('price'))),
            'category'=> sql_sanitize(ucfirst(trim(request('category')))) ,
            'comment'=> sql_sanitize(strtolower(trim(request('comment')))) ,
            'created_at'=> sql_sanitize(request('created_at'))
        ])){
            session()->flash('message','Expense added successfully');
            return redirect('/expenses?page_number=1');

        }else{
            session()->flash('message','Expense didn\'t added successfully');
            return redirect('/expenses/create');
        }

        event(new ExpenseAdded($expense));

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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
//        $this->authorize('view' , $expense) ; //the best   $expenses = Expense::where('user_id',auth()->id()->take(5)->get()

//        abourt_unless(auth()->user()->owns($expens),403);
//        abourt_if($expense->user_id !== auth()->id(),403);
//        return view('',compact('expense'));
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
        $attributes =  $this->validateExpense();

        $expense = Expense::findOrfail($id);
        $expense->expense_name = sql_sanitize(strtolower(trim(request('expense_name')))) ;
        $expense->price = sql_sanitize(trim(request('price'))) ;
        $expense->category = sql_sanitize(ucfirst(trim(request('category')))) ;
        $expense->comment = sql_sanitize(strtolower(trim(request('comment'))))  ;
        $expense->created_at = sql_sanitize(request('created_at'))  ;
        $expense->updated_at = date("Y-m-d h:i:s");

        if($expense->update()){
            session()->flash('message','Expense updated successfully');
            return redirect('/expenses?page_number=1');

        }else{
            session()->flash('message','Expense didn\'t updated successfully');
            return redirect('/expenses/'.$id."/edit");
        }
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
        if(Expense::findOrfail($id)->delete()){
            session()->flash('message','Expense deleted successfully');
        }else{
            session()->flash('message',"Expense didn't delete successfully");
        }
        return redirect('/expenses?page_number=1');
    }

    public function search()
    {
        $search_for = sql_sanitize(strtolower(request('search'))) ;
        $expenses = auth()->user()->expenses()->where('expense_name',$search_for)->paginate(5) ;

        return view('/expenses',compact('expenses'));

    }

    protected function validateExpense()
    {
        return request()->validate(
            [
                'expense_name'=> ['required'] ,
                'price'=> ['required' ] ,
                'category'=> ['required' ] ,

            ]
        );

    }
}
