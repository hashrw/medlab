<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglaDecision extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre_regla',
        'condiciones',
        'diagnostico'
    ];

    protected $casts = [
        'condiciones' => 'array',
        'diagnostico' => 'array'
    ];
}
