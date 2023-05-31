<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'utenti';
    protected $primaryKey = 'username';
    protected $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

}