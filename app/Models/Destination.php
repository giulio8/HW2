<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $table = 'destinazioni';
    public $timestamps = false;


    public function utente()
    {
        return $this->belongsTo(User::class, 'utente');
    }
  
}