<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    protected $table = 'tratte';
    public $timestamps = false;


    public function origine()
    {
        return $this->belongsTo(Airport::class, 'origine');
    }
  
    public function destinazione()
    {
        return $this->belongsTo(Airport::class, 'destinazione');
    }

    public function volo()
    {
        return $this->belongsTo(Booking::class, 'volo');
    }
  
}