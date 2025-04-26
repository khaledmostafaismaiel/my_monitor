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
Route::resource('normal_transactions', 'NormalTransactionsController')->middleware(['auth', 'verified']);
Route::post('/draft_transactions/transfer_to_normal', 'DraftTransactionsController@transferToNormal')->middleware(['auth', 'verified']);
Route::resource('draft_transactions', 'DraftTransactionsController')->middleware(['auth', 'verified']);
Route::resource('blueprint_transactions', 'BlueprintTransactionsController')->middleware(['auth', 'verified']);
Route::resource('categories', 'CategoriesController')->middleware(['auth', 'verified']);
Route::resource('month_years', 'MonthYearsController')->middleware(['auth', 'verified']);

Route::post('/users/verify_otp', 'UsersController@verify_otp');
Route::get('/users/register', 'UsersController@register')->name('login');
Route::post('/users/sign_up', 'UsersController@sign_up');
Route::post('/users/sign_in', 'UsersController@sign_in');
Route::post('/users/sign_out', 'UsersController@sign_out');
Route::resource('/users', 'UsersController')->middleware(['auth', 'verified'])->only('signout');


Route::get('/', function () {
    $user = auth()->user();

    $monthYears = auth()->user()
        ->family
        ->monthYears()
        ->leftJoin('transactions', function ($join) {
            $join->on('transactions.month_year_id', '=', 'month_years.id')
                ->where('transactions.type', "normal");
        })
        ->selectRaw("
            month_years.id as id,
            CONCAT(month_years.year, '-', LPAD(month_years.month, 2, '0')) as month_year,
            SUM(CASE WHEN transactions.direction = 'credit' THEN transactions.price * transactions.quantity ELSE 0 END) as credit,
            SUM(CASE WHEN transactions.direction = 'debit' THEN transactions.price * transactions.quantity ELSE 0 END) as debit
        ")
        ->groupBy('month_years.id')
        ->orderByDesc('month_years.year')
        ->orderByDesc('month_years.month')
        ->paginate(10);

    return view('index', compact(["user", "monthYears"]));
})->middleware(['auth', 'verified']);

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

Route::get('/home', 'HomeController@index')->name('home');
