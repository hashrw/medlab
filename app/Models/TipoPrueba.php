<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPrueba extends Model
{
    protected $fillable = ['nombre'];

    public function pruebas()
    {
        return $this->hasMany(Prueba::class, 'tipo_prueba_id');
    }

}
