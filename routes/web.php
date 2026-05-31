<?php

use Illuminate\Support\Facades\Route;

Route::resource('wallets', 'WalletsController')->middleware(['auth', 'verified']);

Route::post('/normal_transactions/transfer_to_draft', 'NormalTransactionsController@transferToDraft')->middleware(['auth', 'verified']);
Route::resource('normal_transactions', 'NormalTransactionsController')->middleware(['auth', 'verified']);

Route::post('/draft_transactions/transfer_to_normal', 'DraftTransactionsController@transferToNormal')->middleware(['auth', 'verified']);
Route::resource('draft_transactions', 'DraftTransactionsController')->middleware(['auth', 'verified']);

Route::post('/blueprint_transactions/update_and_add_transaction', 'BlueprintTransactionsController@updateAndAddTransaction')->middleware(['auth', 'verified']);
Route::resource('blueprint_transactions', 'BlueprintTransactionsController')->middleware(['auth', 'verified']);

Route::resource('categories', 'CategoriesController')->middleware(['auth', 'verified']);

Route::resource('month_years', 'MonthYearsController')->middleware(['auth', 'verified']);

Route::post('/todos/{todo}/toggle', 'TodosController@toggleStatus')->middleware(['auth', 'verified']);
Route::post('/todos/reorder', 'TodosController@reorder')->middleware(['auth', 'verified']);
Route::resource('todos', 'TodosController')->middleware(['auth', 'verified']);

Route::get('/users/verify_otp', 'UsersController@show_verify_otp')->name('verification.notice');
Route::post('/users/verify_otp', 'UsersController@verify_otp')->name('users.verify_otp');
Route::get('/users/register', 'UsersController@register')->name('login');
Route::post('/users/sign_up', 'UsersController@sign_up')->name('users.sign_up');
Route::post('/users/sign_in', 'UsersController@sign_in')->name('users.sign_in');
Route::post('/users/sign_out', 'UsersController@sign_out')->name('users.sign_out');

Route::get('/', 'HomeController@dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
});
