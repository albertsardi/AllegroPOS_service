<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Auth::routes(['verify' => true]);

Route::prefix('api')->group(function() {
    // form master load
    Route::get('product/{id?}', 'ApiController@getProduct');
    Route::get('customer/{id?}', 'ApiController@getCustomer');
    Route::get('supplier/{id?}', 'ApiController@getSupplier');
    Route::get('coa/{id?}', 'ApiController@getCoa');

    // data save
    Route::prefix('datasave')->group(function() {
        Route::post('/', 'TransController@datasave_PI');
        Route::post('product', 'MasterController@datasave_product');
        Route::post('customer', 'MasterController@datasave_customer');
        Route::post('supplier', 'MasterController@datasave_supplier');
        Route::post('PI', 'TransController@datasave_PI');
    });

    //route send receive mail
    Route::prefix('mail')->group(function() {
        //chart in dashboard
        Route::get('send', 'MailController@mailsend');
        //Route::any('receive', 'MailController@datasave');
    });

    

     // select2 / combobox
     Route::get('select/{jr}', 'SelectController@getSelectData');

    

    Route::post('transsave', 'TransController@transsave');

    // form trans load
    // api ini hanya dipakai tuk ambil data tuk android /using api
    Route::prefix('{jr}')->group(function() {
        Route::get('/', 'ApiController@trans_list');
        Route::get('{id}', 'ApiController@trans');
    });

    
});

Route::prefix('ajax')->group(function() {
    //chart in dashboard
    Route::get('makechart/{id}', 'AppController@makechart');

    // data save
    Route::any('datasave', 'ApiController@datasave');
});


