<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function sintomas(){
        return $this->hasMany(Sintoma::class);
    }

}
