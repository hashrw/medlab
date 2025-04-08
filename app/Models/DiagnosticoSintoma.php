<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DiagnosticoSintoma extends Pivot
{
    use HasFactory;

    protected $table = 'diagnostico_sintoma'; 

    protected $casts = [
        'fecha_diagnostico' => 'datetime:Y-m-d',

    ];
}
