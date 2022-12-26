<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sessiondish extends Model
{
    use HasFactory;

    protected $table='SESSIONDISHES';
    public function taom(){
        return $this->belongsTo(Menu::class,'SIFR','SIFR');
    }
    public function visit(){
        return $this->belongsTo(Visit::class,'VISIT','SIFR');
    }
    public function manyvisit(){
        return$this->hasOneThrough(Payment::class,Visit::class,'SIFR','VISIT','VISIT','SIFR');
    }
}
