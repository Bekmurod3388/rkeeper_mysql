<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    protected $table="VISITS";
    public function payment(){
        return $this->belongsTo(Payment::class,'SIFR','VISIT');
    }
    public function paybinding(){
        return $this->hasMany(Paybinding::class,'SIFR','VISIT');
    }

}
