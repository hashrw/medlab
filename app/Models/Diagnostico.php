<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Diagnostico extends Model
{
    use HasFactory;

    protected $fillable = [
        //'medico_id', // Relación con Médico
        'fecha_diagnostico',
        'tipo_enfermedad',
        'origen',
        'estado_injerto', //estable,pobre
        'dias_desde_trasplante',
        'observaciones',
        'grado_eich',
        'escala_karnofsky',
        'regla_decision_id',
        'estado_id',
        'comienzo_id',
        'infeccion_id'
    ];

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    public function comienzo(): BelongsTo
    {
        return $this->belongsTo(Comienzo::class);
    }

    public function infeccion(): BelongsTo
    {
        return $this->belongsTo(Infeccion::class);
    }

    // Relaciones (muchos a muchos)
    public function sintomas(): BelongsToMany
    {
        return $this->belongsToMany(Sintoma::class)->using(DiagnosticoSintoma::class)->withPivot('fecha_diagnostico', 'score_nih');
    }

    public function pacientes(): BelongsToMany
    {
        return $this->belongsToMany(Paciente::class, 'diagnostico_paciente')->withTimestamps();
    }
}
