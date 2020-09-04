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
Route::resource('expenses', 'ExpensesController')->middleware('auth')/*->middleware('can:update,expense')*/;
Route::get('/expenses/{expense_id}/delete', 'ExpensesController@delete' )->middleware('auth') ;
Route::post('/expenses/search/search', 'ExpensesController@search' )->middleware('auth') ;


Route::resource('/backgrounds', 'BackgroundsController')->middleware('auth');
Route::get('/backgrounds/{background_id}/delete', 'BackgroundsController@delete' )->middleware('auth') ;
Route::get('/expenses/{background_id}/set', 'BackgroundsController@set' )->middleware('auth') ;


Route::resource('/users', 'UsersController');
Route::post('/users/process_sign_in', 'UsersController@process_sign_in');
Route::get('/users/process_sign_out', 'UsersController@process_sign_out');


Route::get('/', function () {
    return view('index');
})->middleware('auth');



Route::get('/not_available', function () {
    return view('not_available');
});



/*
    GET /projects (index)
    GET /projects/create (create)
    GET /projects/1 (show)
    POST /projects (store)
    GET /projects/1/edit (edit)
    PATCH /projects/1 (update)
    DELETE /projects/1 (destroy)


    Route::get('/projects', 'UsersController@index');//GET/expenses
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
