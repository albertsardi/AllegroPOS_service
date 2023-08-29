<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransDetail extends Model
{
	protected $table = 'transdetail'; 
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


