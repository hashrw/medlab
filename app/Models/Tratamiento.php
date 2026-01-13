<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Tratamiento extends Model
{
    use HasFactory;

    // IMPORTANTE: incluye diagnostico_id porque ya existe en DB y lo usas en inferencia
    protected $fillable = [
        'tratamiento',
        'fecha_asignacion',
        'descripcion',
        'paciente_id',
        'medico_id',
        'diagnostico_id',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date:Y-m-d',
    ];

    public function lineasTratamiento()
    {
        return $this->belongsToMany(Medicamento::class, 'medicamento_tratamiento', 'tratamiento_id', 'medicamento_id')
            ->using(MedicamentoTratamiento::class)
            ->withPivot('fecha_ini_linea', 'duracion_linea', 'duracion_total', 'fecha_fin_linea', 'fecha_resp_linea', 'observaciones', 'tomas')
            ->withTimestamps();
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }

    // ---------------------------
    // ESTADO (activo / cerrado)
    // Regla: si existe AL MENOS una línea con fecha_fin_linea NULL -> ACTIVO
    //        si todas las líneas tienen fecha_fin_linea -> CERRADO
    //        si no hay líneas -> "sin_lineas" (útil para detectar datos incompletos)
    // ---------------------------

    public function getEstadoTratamientoAttribute(): string
    {
        // usamos relación cargada si está disponible
        $lineas = $this->relationLoaded('lineasTratamiento')
            ? $this->lineasTratamiento
            : $this->lineasTratamiento()->get();

        if ($lineas->isEmpty()) {
            return 'sin_lineas';
        }

        $hayAbierta = $lineas->contains(function ($m) {
            return empty($m->pivot->fecha_fin_linea);
        });

        return $hayAbierta ? 'activo' : 'cerrado';
    }

    public function getIsActivoAttribute(): bool
    {
        return $this->estado_tratamiento === 'activo';
    }

    public function getIsCerradoAttribute(): bool
    {
        return $this->estado_tratamiento === 'cerrado';
    }

    /**
     * Devuelve la fecha fin global del tratamiento (máxima fecha_fin_linea),
     * o null si hay alguna línea abierta o si no hay líneas.
     */
    public function getFechaFinTratamientoAttribute(): ?string
    {
        $lineas = $this->relationLoaded('lineasTratamiento')
            ? $this->lineasTratamiento
            : $this->lineasTratamiento()->get();

        if ($lineas->isEmpty()) {
            return null;
        }

        // si hay alguna abierta -> no hay fecha fin global
        $hayAbierta = $lineas->contains(fn($m) => empty($m->pivot->fecha_fin_linea));
        if ($hayAbierta) {
            return null;
        }

        $max = $lineas
            ->map(fn($m) => $m->pivot->fecha_fin_linea)
            ->filter()
            ->max();

        return $max ? Carbon::parse($max)->toDateString() : null;
    }

    // (tu accesor existente, no lo toco salvo una protección si fecha_fin_linea es null)
    public function getDuracionTotalAttribute(): int
    {
        $lineas = $this->relationLoaded('lineasTratamiento')
            ? $this->lineasTratamiento
            : $this->lineasTratamiento()->get();

        if ($lineas->isEmpty()) {
            return 0;
        }

        $primera = $lineas->sortBy(fn($m) => $m->pivot->fecha_ini_linea)->first();

        // para duracion_total necesitas una fecha_fin_linea válida.
        // si está activo (hay alguna null), devolvemos 0 (o si quieres, hasta hoy; lo decidimos en el paso 1.1 si lo pides)
        if ($this->estado_tratamiento === 'activo') {
            return 0;
        }

        $ultima = $lineas
            ->filter(fn($m) => !empty($m->pivot->fecha_fin_linea))
            ->sortByDesc(fn($m) => $m->pivot->fecha_fin_linea)
            ->first();

        if (!$primera || !$ultima) {
            return 0;
        }

        $ini = Carbon::parse($primera->pivot->fecha_ini_linea);
        $fin = Carbon::parse($ultima->pivot->fecha_fin_linea);

        return $fin->diffInDays($ini);
    }
}
