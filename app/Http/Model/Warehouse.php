<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
  protected $table = 'masterwarehouse';
  // protected $primaryKey = 'Code';
  //protected $keyType = 'string';
  // protected $fillable = ['Code', 'Name', 'Barcode', 'Category'];
  public $timestamps = false; //disable time stamp
  // const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  // const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
  //protected $appends = ['availability'];

  

}


