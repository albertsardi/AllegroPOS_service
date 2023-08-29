<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
  protected $table = 'mastersalesman';
  protected $primaryKey = 'Code';
  protected $keyType = 'string';
}
