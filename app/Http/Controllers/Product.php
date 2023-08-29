<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $table = 'masterproduct';
  protected $primaryKey = 'Code';
  protected $keyType = 'string';
}
