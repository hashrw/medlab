<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglaDecision extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'prioridad',
        'activo',
        'tipo_recomendacion',
        'descripcion_clinica',
        'condiciones',
        'diagnostico',
    ];

    protected $casts = [
        'condiciones' => 'array',
        'diagnostico' => 'array',
        'activo' => 'boolean',
    ];
}

