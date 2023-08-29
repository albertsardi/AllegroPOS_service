<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Session;

class Account extends Model
{
  protected $table = 'mastercoa';
  // protected $primaryKey = '';
  // protected $keyType = 'string';
  public $timestamps = false;
  //const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  //const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
  protected $fillable = [
				'AccNo', 
				'AccName', 
				'Category',
				'Parent_id',
				'OpenAmount',
				'Memo',
				'Token',
				'id'
			];

  public static function getdata($id=null, $opt=null) {
	  @session_start();
    if ($id==null) {
      //$data = Account::all();
      $data = Account::selectRaw('mastercoa.AccNo as AccNo,AccName,CatName,ifnull(SUM(Amount),0),mastercoa.id')
						->leftJoin('journal as jr','jr.AccNo', '=', 'mastercoa.id')
						//->where('mastercoa.Token', session('user')->Token)
						//->where('jr.Token', session('user')->Token)
            ->groupBy('mastercoa.id', 'AccNo', 'AccName', 'CatName')
						->get();
    } else {
      $data = Account::where('id',$id)->first();
    }
     return (object)['status'=>'OK', 'data'=>$data];
  }

  public static function getAmount($id) {
    $data = Account::where('m.id', $id)
            ->leftJoin('journal as j', 'j.AccNo', '=', 'm.AccNo' )
            ->sum('Amount');
    return $data;
  }

}


