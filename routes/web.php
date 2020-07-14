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

Route::get('/', 'UsersController@index');

Route::get('/add_background', function () {
    return view('add_background');
});

Route::get('/backgrounds', function () {
    return view('backgrounds');
});

Route::get('/delete_background', function () {
    return view('delete_background');
});





Route::resource('/expenses', 'ExpensesController');
Route::get('/expenses/{expense_id}/delete', 'ExpensesController@delete' ) ;

//Route::resource('/backgrounds', 'BackgroundsController');

Route::get('/index', function () {
    return view('index');
});

Route::get('/not_available', function () {
    return view('not_available');
});

Route::get('/search', function () {
    return view('search');
});

Route::get('/set_background', function () {
    return view('set_background');
});

Route::get('/sign_in', function () {
    return view('sign_in');
});

Route::get('/sign_out', function () {
    return view('sign_out');
});




Route::get('/sign_up', 'UsersController@create');
Route::post('/sign_up/create', 'UsersController@store');

/*
    GET /projects (index)
    GET /projects/create (create)
    GET /projects/1 (show)
    POST /projects (store)
    GET /projects/1/edit (edit)
    PATCH /projects/1 (update)
    DELETE /projects/1 (destroy)


    Route::get('/projects', 'UsersController@index');
    Route::get('/projects/create', 'UsersController@create');
    Route::get('/projects/{project_id}', 'UsersController@show');
    Route::post('/projects', 'UsersController@store');
    Route::get('/projects/{project_id}/edit', 'UsersController@edit');
    Route::patch('/projects/{project_id}', 'UsersController@update');
    Route::delete('/projects/{project_id}', 'UsersController@destroy');
                    ==
    Route::resource('projects','UsersController')
*/
