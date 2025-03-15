<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = ['fecha_hora', 'medico_id', 'paciente_id'];

    protected $casts = [
        'fecha_hora' => 'datetime:Y-m-d H:i',
    ];

    public function medico(){
        return $this->belongsTo(Medico::class);
    }

    public function paciente(){
        return $this->belongsTo(Paciente::class);
    }

    public function medicamentos(){
        return $this->belongsToMany(Medicamento::class)->using(CitaMedicamento::class)->withPivot('tomas_dia', 'comentarios', 'inicio', 'fin');
    }
}
