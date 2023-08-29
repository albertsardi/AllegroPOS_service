<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Session;


class CustomerSupplier extends Model
{
	protected $table = 'masteraccount';
	//protected $primaryKey = 'AccCode';
	//protected $keyType = 'string';
	const CREATED_AT = 'CreatedDate'; //change laravel timestamp
	const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
	protected $fillable = [
		//'id',
		'AccCode',
		'AccName',
		'Category',
		'SalesmanCreditLimit',
		'CreditActive',
		'Taxno',
		'TaxName',
		'TaxAddr',
		'PriceChannel',
		'AccNo',
		'Memo',
		'AccType',
		'Active',
		'Token' ];

	public static function getdata($jr=null, $id=null) {
		@session_start();
		$accType = ($jr=='supplier')? 'S' : 'C';
		if ($id==null) {
			//$data = CustomerSupplier::all();
			$data =CustomerSupplier::leftJoin('masteraccountaddr as ma', 'masteraccount.id', '=', 'ma.AccountId')
					->select('masteraccount.*','ma.Phone','Email','Address')
					->where('AccType',$accType)
					->whereRaw('(DefAddr=1)')
					->where('masteraccount.Token', session('user')->Token)
					//->whereRaw("(ma.Token='".session('user')->Token."' or ma.Token is null)")
					->whereRaw("(ma.Token='".session('user')->Token."')")
					//->limit(3)
					->get();

			// $data= DB::select("SELECT masteraccount.*,ma.* FROM masteraccount
			// 	LEFT JOIN masteraccountaddr AS ma ON masteraccount.id=ma.AccountId 
			// 					WHERE AccType='S'
			// 					AND masteraccount.token='ZZXXAA' ");
			 	// dd($data);
			foreach($data as $r) {
				$r->Bal = 12345679; //TODO
			}
		} else {
			$data = CustomerSupplier::where('id',$id)->first();
			if (!empty($data)) {
				$data->Address = DB::table('masteraccountaddr')->where('AccountId',$id)->first();
			}
		}

		return (object)['status'=>'OK', 'data'=>$data];
	}
}
