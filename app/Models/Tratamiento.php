<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Tratamiento extends Model
{
    use HasFactory;

    protected $fillable = ['tratamiento', 'fecha_asignacion', 'descripcion', 'paciente_id', 'medico_id'];

    protected $casts = [
        'fecha_asignacion' => 'date:Y-m-d',
    ];

    // Relación con el modelo LineaTratamiento
    public function lineasTratamiento()
    {
        return $this->belongsToMany(Medicamento::class)->using(MedicamentoTratamiento::class)->withPivot('fecha_ini_linea', 'duracion_linea', 'duracion_total', 'fecha_fin_linea', 'fecha_resp_linea', 'observaciones', 'tomas');

    }

    // Accesor para calcular la duración total del tratamiento
    public function getDuracionTotalAttribute(): int
    {
        if ($this->relationLoaded('lineasTratamiento')) {
            $lineas = $this->lineasTratamiento;
        } else {
            $lineas = $this->lineasTratamiento()->get();
        }

        if ($lineas->isEmpty())
            return 0;

        $primera = $lineas->sortBy(fn($m) => $m->pivot->fecha_ini_linea)->first();
        $ultima = $lineas->sortByDesc(fn($m) => $m->pivot->fecha_fin_linea)->first();

        if (!$primera || !$ultima)
            return 0;

        $ini = Carbon::parse($primera->pivot->fecha_ini_linea);
        $fin = Carbon::parse($ultima->pivot->fecha_fin_linea);

        return $fin->diffInDays($ini);
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