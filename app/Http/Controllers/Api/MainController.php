<?php

/*
 * Allegro.ID multi
 *
 * @author		IntersoftMedia Developers & Contributors
 * @copyright	Copyright (c) 2023 - 2025 Intersoft.web.id
 * @license		https://Intersoft.web.id/license.txt
 * @link		https://app.intersoft.web.id */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Arr;
// use Illuminate\Support\Facades\Http;
// use GuzzleHttp\Client;
// use GuzzleHttp\Exception\RequestException;
// use Illuminate\Support\Facades\Input;
// use App\Http\Model\Account;
// use App\Http\Model\Common;
// use App\Http\Model\CustomerSupplier;
// use App\Http\Model\CustomerSupplierCategory;
// use App\Http\Model\Invoice;
// use App\Http\Model\Salesman;
// use App\Http\Model\Order;
// use App\Http\Model\Product;
// use App\Http\Model\ProductCategory;
// use App\Http\Model\Warehouse;
// use Session;

class MainController extends Controller {
    
    function fdate($dts) {
		//  yyyy-mm-dd -> dd/mm/yyyy
		$dt = strtotime($dts);
		return date('d/m/Y', $dt);
	}

	function unixdate($dts) {
	    // dd/mm/yyyy -> yyyy-mm-dd
	    $dt = explode('/', $dts);
	    return $dt[2].'-'.$dt[1].'-'.$dt[0];
	}
}
