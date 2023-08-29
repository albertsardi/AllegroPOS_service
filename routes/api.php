<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('api')->group(function() {
    
    // select2 / combobox
    Route::get('select/{jr}', 'Api\SelectController@selectData');

    // data load
    Route::get('{jr}/{id?}', 'Api\ApiController@getdata');
     
    // data save
    Route::post('{jr}/save/{id}', 'Api\ApiController@datasave');
    
    
    
    
    
    // form master load
    // Route::get('product/{id?}', 'AjaxController@ajax_getProduct');
    // Route::get('customer/{id?}', 'AjaxController@ajax_getCustomer');
    // Route::get('supplier/{id?}', 'AjaxController@ajax_getSupplier');
    // Route::get('coa/{id?}', 'AjaxController@ajax_getCoa');

    

   

    // Route::post('transsave', 'TransController@transsave');

    // form trans load
    // api ini hanya dipakai tuk ambil data tuk android
    // Route::prefix('{jr}')->group(function() {
    //     Route::get('/', 'ApiController@trans_list');
    //     Route::get('{id}', 'ApiController@trans');
    // });

});

Route::prefix('ajax')->group(function() {
    //chart in dashboard
    Route::get('makechart/{id}', 'AppController@makechart');

    // data save
    Route::any('datasave', 'AjaxController@datasave');
});
