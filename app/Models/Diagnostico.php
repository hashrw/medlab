<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Diagnostico extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_diagnostico',
        'tipo_enfermedad',
        'estado_injerto',      // estable, pobre...
        'observaciones',
        'grado_eich',
        'escala_karnofsky',
        'regla_decision_id',
        'estado_id',
        'comienzo_id',
        'infeccion_id',
        'origen_id',           // FK a tabla origins (manual / inferido)
    ];

    protected $casts = [
        'fecha_diagnostico' => 'date',
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

    public function origen(): BelongsTo
    {
        return $this->belongsTo(Origen::class, 'origen_id');
    }

    // Relaciones (muchos a muchos)
    public function sintomas(): BelongsToMany
    {
        return $this->belongsToMany(Sintoma::class)
            ->using(DiagnosticoSintoma::class)
            ->withPivot('fecha_diagnostico', 'score_nih')
            ->withTimestamps();
    }

    public function pacientes(): BelongsToMany
    {
        return $this->belongsToMany(Paciente::class, 'diagnostico_paciente')
            ->withTimestamps();
    }

    public function regla(): BelongsTo
    {
        return $this->belongsTo(ReglaDecision::class, 'regla_decision_id');
    }
}
