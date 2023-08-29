<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Transhead extends Model
{
  protected $table = 'transhead';
  protected $primaryKey = 'TransNo';
  protected $keyType = 'string';
}
