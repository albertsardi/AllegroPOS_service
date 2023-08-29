<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
  protected $table = 'journal';
  //protected $primaryKey = 'Code';
  //protected $keyType = 'string';
  // protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];
  public $timestamps = false;
  const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp

    public static function Get($id=null) {
      if ($id==null) {
        $data = Journal::all();
      } else {
        //$data = Product::find($id)->first();
        $data = Journal::where('ReffNo',$id)->first();
      }
      return (object)['status'=>'OK', 'data'=>$data];
    }

}


