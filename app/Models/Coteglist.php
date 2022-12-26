<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coteglist extends Model
{
    use HasFactory;
    protected $table='CATEGLIST';
    public function ota(){
        return $this->belongsTo(Coteglist::class,'PARENT','SIFR');
    }
}
