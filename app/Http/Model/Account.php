<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
  protected $table = 'mastercoa as m';
  protected $primaryKey = 'AccNo';
  //protected $keyType = 'string';
  //protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];
  const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
  //protected $appends = ['availability'];

  public static function Get($id=null) {
    if ($id==null) {
      $data = Account::all();
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


