<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function sintomas()
    {
        return $this->hasMany(Sintoma::class);
    }

    public function pacientes()
    {
        return $this->belongsToMany(Paciente::class, 'organo_paciente')
            ->withPivot('score_nih', 'fecha_evaluacion', 'comentario', 'sintomas_asociados')
            ->withTimestamps();
    }

}
