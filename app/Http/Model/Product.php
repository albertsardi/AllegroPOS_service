<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Session;

class Product extends MainModel
{
  protected $table = 'masterproduct';
  //protected $primaryKey = 'Code';
  //protected $keyType = 'string';
  const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
  protected $fillable = [
              'Code',
              'Name',
              'Category',
              'Brand',
              'Type',
              'MinStock',
              'MackStock',
              'MinOrder',
              'StockProduct',
              'ActiveProduct',
              'canBuy',
              'canSell',
              'UOM',
              'SellPrice',
              'Barcode',
              'HppBy',
              'AccHppNo',
              'AccPurchaseNo',
              'AccSellNo',
              'AccInventoryNo',
              'Memo',
              'Token' ];

  //protected $appends = ['availability'];

  public static function getdata($id=null) {
    @session_start();
    if ($id==null) {
      $data = Product::where('Token', session('user')->Token)->get();
    } else {
      $data = Product::where('id',$id)
      ->where('Token', session('user')->Token)
      ->first();
    }
     return (object)['status'=>'OK', 'data'=>$data];
  }

  // public static function getStock($id) {
  //   $data = Trans::where('ProductCode', $id)->get();
  //   return (object)['status'=>'OK', 'data'=>$data];
  // }

}


