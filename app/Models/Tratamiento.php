<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tratamiento extends Model
{
    use HasFactory;
    
    
    protected $fillable = ['tratamiento','fecha_asignacion','descripcion','duracion_trat','medico_id','paciente_id'];

    protected $casts = [
        'fecha_asignacion' => 'date:Y-m-d',
    ];

    // Relación con el modelo LineaTratamiento
    public function lineasTratamiento()
    {
        return $this->belongsToMany(Medicamento::class)->using(MedicamentoTratamiento::class)->withPivot(['fecha_ini_linea',    // Fecha de inicio de la línea de tratamiento
                        'duracion_linea',     // Duración de la línea de tratamiento
                        'duracion_total',     // Duración total del tratamiento
                        'fecha_fin_linea',    // Fecha de fin de la línea de tratamiento
                        'fecha_resp_linea',   // Fecha de respuesta a la línea de tratamiento
                        'observaciones',      // Observaciones de la línea de tratamiento
                        'tomas',              // Número de tomas
                    ]);
    }
    
    // Accesor para calcular la duración total del tratamiento
    public function getDuracionTotalAttribute()
    {
        $primeraLinea = $this->lineasTratamiento->sortBy('fecha_inicio')->first();
        $ultimaLinea = $this->lineasTratamiento->sortByDesc('fecha_fin')->first();

        if ($primeraLinea && $ultimaLinea) {
            return $ultimaLinea->fecha_fin->diffInDays($primeraLinea->fecha_inicio);
        }

        return 0;
    }
    
}