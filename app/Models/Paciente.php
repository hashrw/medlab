<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nuhsa',
        'fecha_nacimiento',
        'peso',
        'altura',
        'sexo'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /*--------------------------------------------------------------
     | RELACIÓN CON USER
     | User.paciente_id → pacientes.id
     --------------------------------------------------------------*/
    public function usuarioAcceso()
    {
        return $this->hasOne(User::class, 'paciente_id');
    }

    /*--------------------------------------------------------------
     | TRASPLANTES
     --------------------------------------------------------------*/
    public function trasplantes()
    {
        return $this->hasMany(Trasplante::class);
    }

    /*--------------------------------------------------------------
     | TRATAMIENTOS (M:N)
     --------------------------------------------------------------*/
    public function tratamientos()
    {
        return $this->belongsToMany(Tratamiento::class)
            ->using(PacienteTratamiento::class)
            ->withPivot('paciente_id', 'tratamiento_id');
    }

    /*--------------------------------------------------------------
     | ÓRGANOS
     --------------------------------------------------------------*/
    public function organos()
    {
        return $this->belongsToMany(Organo::class, 'organo_paciente')
            ->withPivot('score_nih', 'fecha_evaluacion', 'comentario', 'sintomas_asociados')
            ->withTimestamps();
    }

    /*--------------------------------------------------------------
     | SÍNTOMAS
     --------------------------------------------------------------*/
    public function sintomas()
    {
        return $this->belongsToMany(Sintoma::class, 'paciente_sintoma')
            ->withPivot('fecha_observacion', 'activo', 'fuente')
            ->wherePivot('activo', true)
            ->withTimestamps();
    }

    /*--------------------------------------------------------------
     | DIAGNÓSTICOS
     --------------------------------------------------------------*/
    public function diagnosticos(): BelongsToMany
    {
        return $this->belongsToMany(Diagnostico::class, 'diagnostico_paciente')
            ->withTimestamps();
    }

    /*--------------------------------------------------------------
     | ACCESOR: Edad
     --------------------------------------------------------------*/
    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento
            ? now()->diffInYears($this->fecha_nacimiento)
            : null;
    }

    /*--------------------------------------------------------------
     | ACCESOR: Categoría IMC
     --------------------------------------------------------------*/
    public function getICMCategoriaAttribute()
    {
        $imc = $this->imc;

        if (!$imc) {
            return null;
        }

        return match (true) {
            $imc < 18.5 => 'Bajo Peso',
            $imc < 25   => 'Normal',
            $imc < 30   => 'Sobrepeso',
            $imc < 35   => 'Obesidad II',
            $imc < 40   => 'Obesidad III',
            default     => 'Obesidad Mórbida',
        };
    }

    /*--------------------------------------------------------------
     | VALIDACIÓN: edad mínima >= 5 años
     --------------------------------------------------------------*/
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($paciente) {
            if ($paciente->fecha_nacimiento &&
                now()->diffInYears($paciente->fecha_nacimiento) < 5) {

                throw new \Exception(
                    'La fecha de nacimiento no puede indicar menos de 5 años.'
                );
            }
        });
    }
}
