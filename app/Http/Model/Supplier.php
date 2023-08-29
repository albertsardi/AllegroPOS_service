<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
  protected $table = 'masteraccount';
  protected $primaryKey = 'Code';
  protected $keyType = 'string';
}
