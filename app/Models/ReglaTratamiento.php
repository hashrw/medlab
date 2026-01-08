<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglaTratamiento extends Model
{
    use HasFactory;

    protected $table = 'regla_tratamientos';

    protected $fillable = [
        'nombre',
        'prioridad',
        'activo',
        'condiciones',
        'acciones',
        'fuente',
        'observaciones',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'condiciones' => 'array',
        'acciones' => 'array',
    ];
}
