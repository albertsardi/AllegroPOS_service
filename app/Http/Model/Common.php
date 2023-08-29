<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
  protected $table = 'common';
  //protected $primaryKey = 'Code';
  //protected $keyType = 'string';
  //protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];
  //const CREATED_AT = 'create_time'; //change laravel timestamp
  //const UPDATED_AT = 'update_time'; //change laravel creator stamp
  //protected $appends = ['availability'];
  protected $guarded = ['id']; 
  public $timestamps = false; //disable time stamp

  public static function getData($type) {
      $data =  Common::where('category',$type)->get();
      foreach($data as &$dt) {
        $dt['Qty'] = 12345;
      }
      return (object)['status'=>'OK', 'data'=>$data];
  }

}


