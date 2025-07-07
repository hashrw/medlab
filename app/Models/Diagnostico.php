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

        'tipo_enfermedad',
        'origen',
        'observaciones',
        'regla_decision_id',
        'estado_id',
        'comienzo_id',
        'infeccion_id'

    ];

    protected $guarded = ['cie10']; // Protege el campo cie10 contra ediciones

    public function enfermedad(): BelongsTo
    {
        return $this->belongsTo(Enfermedad::class);
    }

    // Relación con el modelo Paciente
    public function pacientes(): BelongsToMany
    {
        return $this->belongsToMany(Paciente::class)->using(DiagnosticoPaciente::class)->withPivot('diagnostico_id', 'paciente_id');
    }

    // Relación con el modelo Sintoma (muchos a muchos)
    public function sintomas(): BelongsToMany
    {
        return $this->belongsToMany(Sintoma::class)->using(DiagnosticoSintoma::class)->withPivot('fecha_diagnostico', 'score_nih');
    }

    // Relación con el modelo Enfermedad (muchos a muchos)
    public function enfermedades(): BelongsToMany
    {
        return $this->belongsToMany(Enfermedad::class)->using(DiagnosticoEnfermedad::class)->withPivot('grado_eich', 'escala_karnofsky');
    }



    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }
}
