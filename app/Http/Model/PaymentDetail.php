<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Paymentdetail extends Model
{
	protected $table = 'transpaymentarap'; 
	public $timestamps = false;
	protected $fillable = [
		'InvNo',
		'AmountPaid',
		'AmountAdj',
		'TransNo',
		'Memo',
		'Token',
	];
}


