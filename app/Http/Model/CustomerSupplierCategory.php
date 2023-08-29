<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CustomerSupplierCategory extends Model
{
  protected $table = 'masteraccountcategory';
  // protected $primaryKey = 'Code';
  //protected $keyType = 'string';
  protected $fillable = ['Category', 'Memo', 'Active'];
  public $timestamps = false; //disable time stamp
  //const CREATED_AT = 'CreatedDate'; //change laravel timestamp
  //const UPDATED_AT = 'UpdatedDate'; //change laravel creator stamp
}


