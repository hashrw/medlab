<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'tipo_prueba_id', 'fecha', 'resultado', 'comentario', 'paciente_id'];

    // si solo fecha:
    protected $casts = ['fecha' => 'date:Y-m-d'];
    // si fecha+hora:
    // protected $casts = ['fecha' => 'datetime:Y-m-d H:i'];

    public function tipo_prueba()
    {
        return $this->belongsTo(TipoPrueba::class, 'tipo_prueba_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }



}
