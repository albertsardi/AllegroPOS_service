<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  protected $table = 'mastermycompany';
  protected $primaryKey = 'Name';
  protected $keyType = 'string';
  public $timestamps = false; //disable time stamp
  protected $fillable = ['Name','LogoPath','Address','DeliveryAddress','Phone','Fax','TaxNo','Website','Email']; 
  //const CREATED_AT = 'create_time'; //change laravel timestamp
  //const UPDATED_AT = 'update_time'; //change laravel creator stamp
  //protected $appends = ['availability'];

//   public static function Get($id=null) {
//     if ($id==null) {
//       $data = Product::all();
//     } else {
//       //$data = Product::find($id)->first();
//       $data = Product::where('id',$id)->first();
//     }
//      //$data=Product->fill(['Qty' => 1234567890]);
//      //$data->text('description');
//      return (object)['status'=>'OK', 'data'=>$data];
//   }

}


