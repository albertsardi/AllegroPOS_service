<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Orderdetail extends Model
{
	protected $table = 'orderdetail'; 
	public $timestamps = false;
	protected $fillable = [
		'TransNo', 
		'ProductCode', 
		'ProductName',
		'Qty',
		'UOM',
		'Price',
		'DiscPercentD',
		'Cost',
		'Memo',
		'Token',
		'trans_id'
	];
}


