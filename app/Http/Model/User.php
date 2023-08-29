<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
  protected $table = 'masteruser';
  protected $primaryKey = 'user_id';
  //protected $keyType = 'string';
  //protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];

  public static function Get($id=null) {
    $data = User::where('id',$id)->first();
     return (object)['status'=>'OK', 'data'=>$data];
  }

  public static function GetByLoginPassword($login, $password) {
    $login = trim(strtolower($login));
    $data = User::where('LoginName',$login)->where('LoginPassword',$password)->first();
    return (object)['status'=>'OK', 'data'=>$data];
  }

  public static function GetProfile($id=null) {
    $data =  DB::table('masteruserprofile')->where('user_id',$id)->first();
    return (object)['status'=>'OK', 'data'=>$data];
  }

}
