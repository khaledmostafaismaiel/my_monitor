<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('transactions', 'TransactionsController')->middleware('auth');

Route::resource('categories', 'CategoriesController')->middleware('auth');

Route::post('/users/sign_in', 'UsersController@sign_in');
Route::post('/users/sign_out', 'UsersController@sign_out');
Route::resource('/users', 'UsersController');


Route::get('/', function () {
    $user = auth()->user();

    $transactions = \App\Transaction::where('family_id', auth()->user()->family_id)
        ->selectRaw("
            DATE_FORMAT(date, '%Y-%m') as month_year,
            SUM(CASE WHEN type = 'credit' THEN price * quantity ELSE 0 END) as credit,
            SUM(CASE WHEN type = 'debit' THEN price * quantity ELSE 0 END) as debit,
            (SUM(CASE WHEN type = 'credit' THEN price * quantity ELSE 0 END) - SUM(CASE WHEN type = 'debit' THEN price * quantity ELSE 0 END)) as undocumented
        ")
        ->groupBy('month_year')
        ->orderBy('month_year', 'desc')
        ->paginate(10);

    return view('index', compact(["user", "transactions"]));
})->middleware('auth');

/*
    GET /projects (index)
    GET /projects/create (create)
    GET /projects/1 (show)
    POST /projects (store)
    GET /projects/1/edit (edit)
    PATCH /projects/1 (update)
    DELETE /projects/1 (destroy)


    Route::get('/projects', 'UsersController@index');//GET/transactions
    Route::get('/projects/create', 'UsersController@create');//GET/add_expense
    Route::get('/projects/{project_id}', 'UsersController@show');//GET/specific expense
    Route::post('/projects', 'UsersController@store');//POST/add_expense form
    Route::get('/projects/{project_id}/edit', 'UsersController@edit');//GET/edit_expense
    Route::patch('/projects/{project_id}', 'UsersController@update');//PATCH/edit_expense form
    Route::delete('/projects/{project_id}', 'UsersController@destroy');//DELETE/specific expense form
                    ==
    Route::resource('projects','UsersController')
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
