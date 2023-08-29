<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Session;

class Payment extends Model
{
	protected $table = 'transpaymenthead';
	//protected $primaryKey = 'Code';
	//protected $keyType = 'string';
	public $timestamps = false;
	const CREATED_AT = 'CreatedDate'; //change laravel timestamp
	const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
	protected $fillable = [
		'TransNo',
		'TransDate',
		'AccCode',
		'AccName',
		'Payment',
		'toAccNo',
		'fromAccNo',
		'CreatedBy',
		'CreatedDate',
		'Memo',
		'Status',
		'Total',
		'Token',
	];
	
	public static function getdata($jr, $id=null, $option=null) {
		@session_start();
		$id = 'AR.1800004';
		if ($id==null) {
			//$data = Payment::all();
			$data = Payment::where('Token', session('Token'))->get();
		} else {
			//dd($id);
			$data = Payment::where('TransNo',$id)
			//->where('Token', Session::get('Token'))
			->first();
			//dd($data->TransNo);
			//dump(['dd',$data]);
			if(!empty($data)) {
				$data['detail'] = DB::table('transpaymentarap as arap')
								->select(['arap.*','inv.TransDate as InvDate', 'inv.Total as InvAmount'])
								->leftJoin('TransInvoice as inv', 'inv.TransNo', '=', 'arap.InvNo')
								->where('arap.TransNo', $data->TransNo)
								->get();
			}
		}
		return (object)['status'=>'OK', 'data'=>$data];
	}

}


