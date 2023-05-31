<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $table = 'aeroporti';
    protected $primaryKey = 'iata_code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

}