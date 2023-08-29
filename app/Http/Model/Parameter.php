<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;



class Parameter extends Model
{
  protected $table = 'parameter';
  public $timestamps = false;
  

  public static function GetData() {
    $res = Parameter::get();
    $out = [];
    foreach($res as $r) {
        $key = strtolower($r->ParamGroup.'-'.$r->ParamName);
        $out[$key] = $r->ParamValue;
    }
    return (object)['status'=>'OK', 'data'=>$out];
  }

  public static function getVal($pname) {
    //get default value
    $defValue = [
      ['date.dateformat', 'dd/mm/yyyy'],
    ];

    $pname = str_replace('.', '_', $pname);
    $defKey = array_search($pname, array_column($defValue, '0'));
    $def = ($defKey!=false)? $defValue[$defKey][1]:'';
    $res = Parameter::whereRaw("concat(ParamGroup,'_',ParamName)='$pname' ")->first();

    return (!empty($res)? $res->ParamValue:$def);
  }

}


