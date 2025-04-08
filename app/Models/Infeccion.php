<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infeccion extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function diagnosticos(){
        return $this->belongsTo(Diagnostico::class);
    }
}
