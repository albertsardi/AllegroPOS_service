<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
  protected $table = 'transpaymenthead';
  //protected $primaryKey = 'Code';
  //protected $keyType = 'string';
  // protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];
  //public $timestamps = false;
  const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
  
  public static function Get($id=null, $option=null) {
    if ($id==null) {
      $data = Payment::all();
    } else {
      $data = Payment::where('TransNo',$id)->first();
      if(!empty($data)) {
        $data['detail'] = DB::table('transpaymentarap as arap')
                        ->select(['arap.*','inv.TransDate as InvDate', 'inv.Total as InvTotal'])
                        ->leftJoin('TransInvoice as inv', 'inv.TransNo', '=', 'arap.InvNo')
                        ->where('arap.InvNo', $data->TransNo)
                        ->get();
      }
    }
     return (object)['status'=>'OK', 'data'=>$data];
  }

}


