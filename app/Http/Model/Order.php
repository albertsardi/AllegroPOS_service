<?php

namespace App\Http\Model;

use App\MainModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
  protected $table = 'orderhead';
  //protected $primaryKey = 'Code';
  //protected $keyType = 'string';
  // protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];
  //public $timestamps = false;
  const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
  //protected $appends = ['availability'];

  public static function Get($jr, $id=null, $option=null) {
    //if (in_array($jr, ['SI','IN'])) return GetInvoice($id, $option); //for Invoice
    if ($id==null) {
      $data = Order::whereRaw("left(TransNo,2)='$jr'")->get()->toArray();
    } else {
      //$data = Product::find($id)->first();
      $data = Order::where('TransNo',$id)->first();
      if (!empty($data)) {
        $data['detail'] = DB::table('orderdetail')
                          ->where('TransNo', $id)
                          ->get()->toArray();
        $data=(object)$data;
      }
    }
    return (object)['status'=>'OK', 'data'=>$data];
  }

  function GetInvoice($id=null, $option=null) {
    if ($id==null) {
      $data = Order::all();
    } else {
      //$data = Product::find($id)->first();
      $data = Order::where('TransNo',$id)->first();
      $data['detail'] = DB::table('transdetail')
                        ->where('InvNo', $data->TransNo)
                        ->get();
    }
     return (object)['status'=>'OK', 'data'=>$data];
  }

  public static function GetListInvoiceUnpaid($option=null) {
    $data = DB::select("SELECT th.TransNo,TransDate,Total,IFNULL(SUM(AmountPaid),0)AS InvPaid,(Total-IFNULL(SUM(AmountPaid),0))AS InvUnpaid
                        FROM transhead th
                        LEFT JOIN transpaymentarap arap ON arap.InvNo=th.TransNo
                        WHERE LEFT(th.TransNo,2) IN ('SI','IN')
                        GROUP BY TransNo,TransDate,Total
                        HAVING InvUnpaid<>0");
     return (object)['status'=>'OK', 'data'=>$data];
  }

  public static function GetProductCount($transno) {
    return DB::table('orderdetail')->where('TransNo', $transno)->sum('Qty');
  }

}


