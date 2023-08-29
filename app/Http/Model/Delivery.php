<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Delivery extends Model
{
    protected $table = 'transdelivery';
    //protected $primaryKey = 'Code';
    //protected $keyType = 'string';
    // protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];
    //public $timestamps = false;
    const CREATED_AT = 'CreatedDate'; //change laravel timestamp
    const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
    protected $fillable = ['TransNo','TransDate','OrderNo','CarNo','Driver','AccCode','TaxAmount','FreightPercent','FreightAmount','DiscPercentH','DiscAmountH','Note','CreatedBy','Total','TotalPaid','TotalReturn'];
    //protected $appends = ['availability'];

    public static function Get($jr, $id=null, $option=null) {
        if ($id==null) {
            $data = Delivery::whereRaw("left(TransNo,2)='$jr'")->get()->toArray();
        } else {
            // $data = Delivery::where('TransNo',$id)->first();
            $data = Delivery::leftJoin('orderhead as oh', 'oh.TransNo','=','transdelivery.OrderNo')
                    ->select('transdelivery.*', 'oh.TransDate as OrderDate','oh.AccName','oh.Deliveryto')
                    ->where('transdelivery.TransNo',$id)->first();
            if(!empty($data)) {
                $data['detail'] = DB::table('transdetail')
                                ->where('DONo', $id)
                                ->get();
            }
        }
        return (object)['status'=>'OK', 'data'=>$data];
    }
}


