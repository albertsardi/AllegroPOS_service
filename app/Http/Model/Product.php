<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $table = 'masterproduct';
  // protected $primaryKey = 'Code';
  //protected $keyType = 'string';
  protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];
  const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
  //protected $appends = ['availability'];

  // public static function Get($id=null) {
  //   if ($id==null) {
  //     $data = Product::all();
  //   } else {
  //     //$data = Product::find($id)->first();
  //     $data = Product::where('id',$id)->first();
  //   }
  //    //$data=Product->fill(['Qty' => 1234567890]);
  //    //$data->text('description');
  //    return (object)['status'=>'OK', 'data'=>$data];
  // }

  public function getStock($id) {
    $data = Trans::where('ProductCode', $id)->get();
    return (object)['status'=>'OK', 'data'=>$data];
  }

}


