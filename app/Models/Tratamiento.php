<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Tratamiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'activo',
        'tratamiento',
        'fecha_asignacion',
        'fecha_inicio',
        'fecha_cierre',
        'descripcion',
        'paciente_id',
        'medico_id',
        'diagnostico_id',
        'duracion_linea',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_asignacion' => 'date:Y-m-d',
        'fecha_inicio' => 'date:Y-m-d',
        'fecha_cierre' => 'date:Y-m-d',
    ];

    public function lineasTratamiento()
    {
        return $this->belongsToMany(Medicamento::class, 'medicamento_tratamiento', 'tratamiento_id', 'medicamento_id')
            ->using(MedicamentoTratamiento::class)
            ->withPivot('fecha_ini_linea', 'duracion_linea', 'fecha_fin_linea', 'fecha_resp_linea', 'observaciones', 'tomas')
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

    // Estado global desde cabecera
    public function getEstadoTratamientoAttribute(): string
    {
        // Si quieres mantener "sin_lineas" para detectar incompletos, lo dejamos como accesor auxiliar,
        // pero el estado clínico real lo define activo/fecha_cierre.
        if ($this->fecha_cierre) {
            return 'cerrado';
        }
        return $this->activo ? 'activo' : 'borrador';
    }

    public function getIsActivoAttribute(): bool
    {
        return $this->estado_tratamiento === 'activo';
    }

    public function getIsCerradoAttribute(): bool
    {
        return $this->estado_tratamiento === 'cerrado';
    }

    public function getFechaFinTratamientoAttribute(): ?string
    {
        return $this->fecha_cierre ? Carbon::parse($this->fecha_cierre)->toDateString() : null;
    }

    // Duración global (solo si está cerrado)
    public function getDuracionTotalAttribute(): ?int
    {
        if (!$this->fecha_inicio || !$this->fecha_cierre) {
            return null;
        }

        $ini = Carbon::parse($this->fecha_inicio);
        $fin = Carbon::parse($this->fecha_cierre);

        return $fin->diffInDays($ini);
    }
}