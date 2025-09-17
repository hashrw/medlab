<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tratamiento extends Model
{
    use HasFactory;

    protected $fillable = ['tratamiento', 'fecha_asignacion', 'descripcion', 'duracion_trat'];

    protected $casts = [
        'fecha_asignacion' => 'date:Y-m-d',
    ];

    // Relación con el modelo LineaTratamiento
    public function lineasTratamiento()
    {
        return $this->belongsToMany(Medicamento::class)->using(MedicamentoTratamiento::class)->withPivot('fecha_ini_linea', 'duracion_linea', 'duracion_total', 'fecha_fin_linea', 'fecha_resp_linea', 'observaciones', 'tomas');

    }

    // Accesor para calcular la duración total del tratamiento
    public function getDuracionTotalAttribute()
    {
        $primeraLinea = $this->lineasTratamiento->sortBy('fecha_ini_linea')->first();
        $ultimaLinea = $this->lineasTratamiento->sortByDesc('fecha_fin_linea')->first();

        if ($primeraLinea && $ultimaLinea) {
            return $ultimaLinea->fecha_fin_linea->diffInDays($primeraLinea->fecha_ini_linea);
        }

        return 0;
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    /*public function pacientes(){
        return $this->belongsToMany(Paciente::class)->using(PacienteTratamiento::class)->withPivot('paciente_id','tratamiento_id');
    }*/

}