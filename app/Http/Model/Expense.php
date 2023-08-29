<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Session;

class Expense extends Model
{
  protected $table = 'transexpense';

  public static function getdata($id=null, $option=null) {
    @session_start();
    if ($id==null) {
      $data = Expense::orderBy('TransDate')
              ->where('Token', Session::get('Token'))
              ->get();
    } else {
      $data = Expense::where('TransNo',$id)->first();
      if(!empty($data)) {
        $data['detail'] = DB::table('journal as j')
                        ->select(['j.*','m.AccName'])
                        ->leftJoin('mastercoa as m', 'm.AccNo', '=', 'j.AccNo')
                        ->where('ReffNo', $data->TransNo)
                        ->where('j.Token', Session::get('Token'))
                        ->where('m.Token', Session::get('Token'))
                        ->get();
			  foreach($data['detail'] as $dt) {
            $dt->Debet  = debet($dt->Amount);
          	$dt->Credit = credit($dt->Amount);
        } 
      }
    }
     return (object)['status'=>'OK', 'data'=>$data];
  }

 

}

 // func lib
 function debet($val) {
  if($val>0) { return $val; } else {return 0; }
}
function credit($val) {
  if($val<0) { return -$val; } else {return 0; }
}

