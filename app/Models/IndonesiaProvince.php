<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndonesiaProvince extends Model
{
    protected $table = 'indonesia_provinces';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'code';
}