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

Route::get('/', function () {
    return view('welcome');
});

//under construction
Route::get('/under/construction', 'LoginController@underConstruction')->name('under.construction');
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::group(['middleware' => 'auth.check'], function () {
    //user validity expired
    Route::get('/user/expired', 'LoginController@userExpired')->name('user.expired');
    
    //common routes
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('/user/profile', 'UserController@profileView')->name('user.profile');
    Route::post('/user/profile', 'UserController@profileUpdate')->name('user.profile.action');

    //user routes
    Route::group(['middleware' => ['user.role:0,1,2']], function () {
        //branch
        Route::resource('branch', 'BranchController');

        //product
        Route::resource('product', 'ProductController');

        //account
        Route::resource('account', 'AccountController');

        //staff
        Route::resource('employee', 'EmployeeController');

        //production
        Route::resource('production', 'ProductionController');

        //purchases
        Route::resource('purchase', 'PurchaseController');

        //sales
        Route::get('/sale/{id}/invoice', 'SaleController@invoice')->name('sale.invoice');
        Route::resource('sale', 'SaleController');

        //expenses
        Route::resource('expense', 'ExpenseController');

        //vouchers
        Route::resource('voucher', 'VoucherController');

        //reports
        Route::get('reports/account-statement', 'ReportController@accountStatement')->name('report.account-statement');

        //ajax urls
        Route::group(['middleware' => 'is.ajax'], function () {
            
        });
    });
});
