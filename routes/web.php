<?php

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


Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('dashboard');

    Route::resource('users', 'UserController');
    Route::get('user/list', 'UserController@list')->name('user-list');
    Route::post('user/password-change', 'UserController@passwordChange')->name('password-change');

    /* PRODUCT */
    Route::resource('products', 'ProductController');
    Route::get('product/list', 'ProductController@list')->name('product-list');
    Route::post('product/edit/get-form', 'ProductController@edit')->name('get-product-form');
    Route::post('product/update', 'ProductController@update')->name('products-update');
    Route::post('product/search', 'ProductController@search')->name('products-search');

    /* PRODUCT */
    Route::resource('projects', 'ProjectController');
    Route::get('project/list', 'ProjectController@list')->name('project-list');
    Route::post('project/edit/get-form', 'ProjectController@edit')->name('get-project-form');
    Route::post('project/update', 'ProjectController@update')->name('projects-update');
    Route::post('project/search', 'ProjectController@search')->name('projects-search');

    /* Budget */
    Route::resource('budgets', 'BudgetController');
    Route::get('budget/page/{type}', 'BudgetController@index')->name('budget.index');
    Route::get('budget/list', 'BudgetController@list')->name('budgets.list');
    Route::post('budget/show', 'BudgetController@show')->name('budgets.show');
    Route::post('budget/edit', 'BudgetController@edit')->name('budgets.edit');
    Route::post('budget/update', 'BudgetController@update');

    /* ORDER */
    Route::resource('expenses', 'ExpenseController');
    Route::get('expense/page/{type}', 'ExpenseController@index')->name('expense.index');
    Route::post('expense/edit', 'ExpenseController@edit')->name('expenses.edit');
    Route::get('expense/list', 'ExpenseController@list')->name('expenses.list');
    Route::post('expense/show', 'ExpenseController@show')->name('expense.show');
    Route::post('expense/product/search', 'ExpenseController@productSearch')->name('expense.product.search');
    Route::post('expense/update', 'ExpenseController@update');

    /* Report */
    Route::get('report/index', 'ReportController@index')->name('report.index');
});



