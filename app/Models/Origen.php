<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Origen extends Model
{
    protected $fillable = ['origen'];

    public function medicos(){
        return $this->hasMany(Diagnostico::class);
    }
}
