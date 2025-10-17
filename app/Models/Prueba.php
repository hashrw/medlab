<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'tipo_prueba_id', 'fecha', 'resultado', 'comentario'];

    protected $casts = ['fecha' => 'datetime:Y-m-d'];

    public function tipo_prueba()
    {
        return $this->belongsTo(TipoPrueba::class, 'tipo_prueba_id');
    }


}
