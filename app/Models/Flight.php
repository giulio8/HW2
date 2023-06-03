<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $table = 'prenotazioni';
    public $timestamps = false;


    public function origine()
    {
        return $this->belongsTo(Airport::class, 'origine');
    }
  
    public function destinazione()
    {
        return $this->belongsTo(Airport::class, 'destinazione');
    }

    public function utente()
    {
        return $this->belongsTo(User::class, 'utente');
    }
  
}