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

//API route
include('api.php');

Route::get('trans', function () {
return 'trans';
//return View::make('trans');
});

/*Route::get('/', function () {
session_start();
//$_SESSION['user'] = '12345';
Session::set('user_auth','12345');
if (empty(Session::get('user'))) {
    return view('login');
} else {
    return App::call('App\Http\Controllers\AppController@dashboard');
}
});*/

//app
// Route::any('/', 'AppController@dashboard');
// Route::get('public/dashboard', 'AppController@dashboard');
// Route::get('reportall', 'AppController@reportall');
// Route::get('setting', 'AppController@setting');
// Route::post('setting', 'AppController@setting_save');
Route::get('logout', 'AppController@logout');
Route::get('login', 'AppController@login');
Route::post('login', 'AppController@checklogin');
// Route::get('upload', 'FileUploadController@upload'); // upload file
// Route::post('upload/proses', 'FileUploadController@proses_upload'); // process upload file
// https://www.malasngoding.com/membuat-upload-file-laravel/

//dashboard
//Route::get('ajax_makechart/{id}', 'AppController@makechart');

//load data
Route::get('loadtrans/{id}', 'DataController@loadTrans');

//server side
//ajax list Master
Route::get('_post_datalist/{id}/{page}/{find}', 'MasterController@post_datalist');

//server side / API--------------------

// list
Route::get('ajax_translist/{jr}', 'AjaxController@ajax_translist')->name('ajax_translist');
Route::get('ajax_datalist/{jr}', 'AjaxController@ajax_datalist')->name('ajax_datalist');

// form master load
//Route::get('api/product/{id?}', 'AjaxController@ajax_getProduct');
// Route::get('api/customer/{id?}', 'AjaxController@ajax_getCustomer');
// Route::get('api/customer/{id?}', 'AjaxController@ajax_getCustomer');
// Route::get('api/supplier/{id?}', 'AjaxController@ajax_getSupplier');
// form master save
// Route::get('api/datasave_product', 'AjaxController@dataload_product');
// Route::post('api/datasave_product', 'AjaxController@datasave_product');
//Route::post('supplier-edit/ajax_post', 'AjaxController@datasave_supplier');
//Route::post('customer-edit/ajax_post', 'AjaxController@datasave_customer');
// form trans load
// Route::get('api/trans/{jr}/{id?}', 'AjaxController@ajax_getTrans');
// form trans save
Route::post('trans-edit/ajax_post', 'TransController@datasave');
// --------------------------------------------

//load data row / lookup
Route::get('trans-edit/_loaddatarow/{jr}/{id}', function ($jr,$id) {
if($jr=='product') {
    $dat = (array)DB::table('masterproduct')->where('Code', $id)->first();
        return json_encode($dat);
    }
});

// Route::group(['middleware' => ['Auth']], function () {
//Route::group( function () {

    Route::any('/', 'AppController@dashboard');
    Route::get('dashboard', 'AppController@dashboard');
    Route::get('reportall', 'AppController@reportall');
    Route::get('setting', 'AppController@setting');
    Route::post('setting', 'AppController@setting_save');
    // Route::get('logout', 'AppController@logout');
    // Route::get('login', 'AppController@login');
    // Route::post('login', 'AppController@checklogin');
    Route::get('upload', 'FileUploadController@upload'); // upload file
    Route::post('upload/proses', 'FileUploadController@proses_upload'); // process upload file
    // https://www.malasngoding.com/membuat-upload-file-laravel/

    //dataList
    Route::get('datalist/{jr}', 'DatalistController@datalist');
    Route::get('datalist/{jr}/excel', 'DatalistController@datalist_exportexcel');
    Route::get('datalist/{jr}/pdf', 'DatalistController@datalist_exportpdf');
    Route::get('datalist/{jr}/pdf-usingChromeheadless', 'DatalistController@datalist_exportpdf_usingChromeheadless');
    Route::get('accountdetaillist/{id}', 'DatalistController@accountdetaillist');

    //transList
    Route::get('translist/{jr}', 'DatalistController@translist');
    Route::get('translist/{jr}/excel', 'DatalistController@translist_exportexcel');
    Route::get('translist/{jr}/pdf', 'DatalistController@translist_exportpdf');
    Route::get('translist/{jr}/pdf_usingTPDF', 'DatalistController@translist_exportpdf_usingTPDF');
    Route::get('translist/{jr}/dt_excel', 'DatalistController@translist_dt_exportexcel');
    Route::get('translist/EX', 'JournalBankController@translist');

    //profile
    Route::get('profile', 'ProfileController@profile');
    Route::post('profile', 'ProfileController@profile_save');

    //master Product
    Route::prefix('product')->group(function () {
        Route::get('view/{id}', 'ProductController@dataview');
        Route::get('edit/{id}', 'ProductController@dataedit');
        //Route::post('edit/{id}', 'ProductController@datasave'); //ini route save yg benar
    });

    //master Account
    Route::prefix('coa')->group(function () {
        Route::get('view/{id}', 'AccountController@dataview');
        Route::get('edit/{id}', 'AccountController@dataedit');
        //Route::post('edit/{id}', 'AccountController@datasave'); //ini route save yg benar
    });

    //master customer / supplier
    $mdata = ['customer', 'supplier'];
    foreach($mdata as $jr) {
        Route::prefix($jr)->group(function () {
            Route::get('view/{id}', 'CustomerSupplierController@dataview');
            Route::get('edit/{id}', 'CustomerSupplierController@dataedit');
            //Route::post('edit/{id}', 'CustomerSupplierController@datasave'); //ini route save yg benar
        });
    }

    //edit trans
    // Route::get('trans-edit/{jr}/{id}', 'TransController@transedit')->name('trans-edit');
    // Route::post('trans-edit/{jr}/{id}', 'TransController@transsave');
    //Route::post('transsave', 'TransController@transsave');
    // Route::get("trans-edit/pro/products/batch", 'TransController@testbatch');
    // Route::post('transpaymentsave', 'TransController@transpaysave');

    //invoice SI & PI
    foreach(['PI', 'SI', 'IN'] as $jr) {
        Route::get("view/$jr/{id}", 'InvoiceController@transview');
        Route::get("edit/$jr/{id}", 'InvoiceController@transedit');
        //Route::post("edit/$jr/{id}", 'InvoiceController@transsave'); 
    }

    //order SO & PO
    foreach(['PO', 'SO'] as $jr) {
        Route::get("view/$jr/{id}", 'OrderController@transview');
        Route::get("edit/$jr/{id}", 'OrderController@transedit');
        //Route::post("edit/$jr/{id}", 'OrderController@transsave'); 
    }
    
    //payment AR AP
    foreach(['AR', 'AP'] as $jr) {
        Route::get("view/$jr/{id}", 'PaymentController@transedit');
        Route::get("edit/$jr/{id}", 'PaymentController@transedit');
        //Route::post("edit/$jr/{id}", 'PaymentController@paymentsave');
    }

    //edit cash/bank
    Route::get('edit/cash/{id}', 'JournalBankController@dataedit');
    Route::post('edit/cash/{id}', 'JournalBankController@datasave');
    Route::get('edit/bank/{id}', 'JournalBankController@dataedit');
    //Route::post('edit/bank/{id}', 'JournalBankController@datasave');

    //expense
    Route::get("view/EX/{id}", 'ExpenseController@transview');
    Route::get("edit/EX/{id}", 'ExpenseController@transedit');
    //Route::post("edit/EX/{id}", 'ExpenseController@transsave'); 

    //order
    Route::prefix('order')->group(function () {
        Route::get('createinvoice/{id}', 'TransController@createInvoice');
    });

    //report
    Route::prefix('report')->group(function () {
        Route::get('stockquery', 'ReportController@ReportStockQuery');
        Route::get('{id}', 'ReportController@makereport');
        // Route::get('report/{id}', 'ReportController@examplepdf');
    });

    //koolreport test di web
    Route::get('koolreport-translist/{jr}', 'TransController@koolreport_translist');

    //get data using ajax
    Route::get('getrow/{db}', 'AjaxController@getDBRow');
    Route::get('getdata/{db}', 'AjaxController@getDBData');
    Route::get('getbalanceproduct/{id}', 'AjaxController@getProductSumQty');
    Route::get('getbalanceacc/{id}', 'AjaxController@getAccSumAmount');

    //get data using select box ui
    Route::get('select/{jr}', 'MainController@selectData');

    //Router test for using new technology
    Route::prefix('test')->group(function () {
        Route::get('docraptorpdf', 'ReportController@test_docraptor');
    });

//});

/*
    router tuk coba2
*/
Route::prefix('test')->group(function($e) {
    // form master load
    Route::get('reportPDF', 'TestController@reportPDF');
    Route::get('koolreport/chart', 'TestController@koolreportchart');
    Route::get('koolreport/chart/pdf', 'TestController@koolreportchart_pdf');

    //test all grid
    Route::view('all-grid', 'test-allgrid', ['caption' => 'Test-Grid']);
});





