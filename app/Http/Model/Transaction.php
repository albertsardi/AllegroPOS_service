<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
  protected $table = 'transhead'; 
  //protected $primaryKey = 'Code';
  //protected $keyType = 'string';
  // protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];
  //public $timestamps = false;
  const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp

  public static function Get($jr, $id=null, $option=null) {
    if (in_array($jr, ['SI','IN'])) return Transaction::GetInvoice($id, $option); //for Invoice
    if ($id==null) {
      $data = Transaction::whereRaw("left($fld,2)='$jr'")->get()->toArray();
      foreach($data as &$dt) {
        $dt->Qty = abs($dt->Qty); 
      }
    } else {
      //$data = Transaction::where('TransNo', $id)->first()->toArray();
      $data = Transaction::where('TransNo', $id)->first();
      if(!empty($data)) {
        if ($jr!='DO') {
          $data['detail'] = DB::table('transdetail')
                            ->where('TransNo', $data['TransNo'])
                            ->select('td.*','mp.Name as ProductName')
                            ->leftJoin('masterproduct as mp','mp.Code','=','td.ProductCode')
                            ->get()->toArray();
        } else {
          	$data['detail'] = DB::table('transdetail as td')
                            ->select('td.*','mp.Name as ProductName')
                            ->leftJoin('masterproduct as mp','mp.Code','=','td.ProductCode')
                            ->where('TransNo', $data->TransNo)
                            ->get()->toArray();
        }
        if(!empty($data['detail'])) {
          foreach($data['detail'] as $dt) {
            //$dt->OrderQty = abs($dt->OrderQty); 
            $dt->OrderQty = Transaction::getOrderQty($dt->TransNo, $dt->ProductCode); 
            $dt->SentQty = abs($dt->Qty); 
            $dt->TotSentQty = abs($dt->Qty); 
            // $dt->ReceiveQty = abs($dt->ReceiveQty); 
			unset($dt->Qty);
          }
        }
      } else {
        //$data['detail'] = [];
      }
    }
    return (object)['status'=>'OK', 'data'=>$data];
  }

  public static function GetInvoice($id=null, $option=null) {
    if ($id==null) {
      $data = DB::table('transinvoice')->all();
    } else {
      $data = DB::table('transinvoice')->where('TransNo',$id)->first();
      //dd($data);
      if (!empty($data)) {
        $dat2 = DB::table('transdetail')
                ->where('OrderNo', $data->OrderNo)
                ->get();
        $data->detail = (!empty($dat2))? $dat2 : []; 
      }
    }
     return (object)['status'=>'OK', 'data'=>$data];
  }

  public static function GetListSalesInvoiceUnpaid($option=null) {
      $data = DB::table('transhead as th')->selectRaw('th.TransNo,th.TransDate,th.Total,IFNULL(SUM(AmountPaid),0)AS InvPaid,(Total-IFNULL(SUM(AmountPaid),0))AS InvUnpaid')
              ->leftJoin('transpaymentarap as arap', 'arap.InvNo', '=', 'th.TransNo')
              ->groupBy('th.TransNo', 'TransDate', 'Total')
              ->havingRaw('InvUnpaid<>0')
              ->whereRaw("LEFT(th.TransNo,2) IN ('SI','IN')")
              ->get();
      return (object)['status'=>'OK', 'data'=>$data];
  }

  public static function GetListPurchaseInvoiceUnpaid($option=null) {
      $data = DB::table('transhead as th')->selectRaw('th.TransNo,th.TransDate,th.Total,IFNULL(SUM(AmountPaid),0)AS InvPaid,(Total-IFNULL(SUM(AmountPaid),0))AS InvUnpaid')
              ->leftJoin('transpaymentarap as arap', 'arap.InvNo', '=', 'th.TransNo')
              ->groupBy('TransNo', 'TransDate', 'Total')
              ->havingRaw('InvUnpaid<>0')
              ->whereRaw("LEFT(th.TransNo,2) IN ('PI')")
              ->get();
      return (object)['status'=>'OK', 'data'=>$data];
  }

  public static function getOrderQty($SONo, $PCode) {
    $dat = DB::table('orderdetail')
            ->where('TransNo', $SONo)
            ->where('ProductCode', $PCode)
            ->first();
    return (!empty($dat))? $dat->Qty: 0;
  }

}


