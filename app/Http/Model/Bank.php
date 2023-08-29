<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
  protected $table = 'masterbank';
  protected $primaryKey = 'AccNo';
  //protected $keyType = 'string';
  //protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];
  const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
  //protected $appends = ['availability'];

  public static function Get($id=null) {
    if ($id==null) {
      $data = Bank::all();
    } else {
      //$data = Product::find($id)->first();
      $data = Bank::where('id',$id)->first();
    }
     //$data=Product->fill(['Qty' => 1234567890]);
     //$data->text('description');
     return (object)['status'=>'OK', 'data'=>$data];
  }

}


