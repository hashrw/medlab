<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicamento extends Model
{
    protected $fillable = ['nombre', 'miligramos'];

    public function lineasTratamiento(): BelongsToMany
    {
        return $this->belongsToMany(Tratamiento::class)->using(MedicamentoTratamiento::class)->withPivot('fecha_ini_linea',    // Fecha de inicio de la línea de tratamiento
        'duracion_linea',     // Duración de la línea de tratamiento
        'duracion_total',     // Duración total del tratamiento
        'fecha_fin_linea',    // Fecha de fin de la línea de tratamiento
        'fecha_resp_linea',   // Fecha de respuesta a la línea de tratamiento
        'observaciones',      // Observaciones de la línea de tratamiento
        'tomas',              // Número de tomas
        );             
    }
}
