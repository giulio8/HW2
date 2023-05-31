<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $table = 'aeroporti';
    protected $primaryKey = 'iata_code';
    protected $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

}