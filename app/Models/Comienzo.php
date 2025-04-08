<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comienzo extends Model
{
    use HasFactory;

    protected $fillable = ['tipo_comienzo'];

    public function diagnosticos(){
        return $this->hasMany(Diagnostico::class);
    }
}
