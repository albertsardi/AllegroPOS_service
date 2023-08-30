<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expense extends Model
{
  protected $table = 'transexpense';

  public static function Get($id=null, $option=null) {
    if ($id==null) {
      $data = Expense::all();
    } else {
      $data = Expense::where('TransNo',$id)->first();
      if(!empty($data)) {
        $data['detail'] = DB::table('journal as j')
                        ->select(['j.*','m.AccName'])
                        ->leftJoin('mastercoa as m', 'm.AccNo', '=', 'j.AccNo')
                        ->where('ReffNo', $data->TransNo)
                        ->get();
      }
    }
     return (object)['status'=>'OK', 'data'=>$data];
  }

}


