<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    use HasFactory;
    
    protected $fillable = ['tipo_trasplante','fecha_trasplante','origen_trasplante','identidad_hla',
    'tipo_acondicionamiento','seropositividad_donante','seropositividad_receptor'];

    protected $casts = [
        'fecha_trasplante' => 'date:Y-m-d',
    ];

    public function pacientes()
    {
        return $this->belongsToMany(Paciente::class)->using(PacienteEnfermedad::class)->withPivot('paciente_id','enfermedad');
    }

    public function diagnosticos(){
        return $this->belongsToMany(Diagnostico::class)->using(DiagnosticoEnfermedad::class)->withPivot('grado_eich', 'escala_karnofsky');

    }
    
     /*public function diagnosticos(){
        return $this->hasMany(Diagnostico::class);
    }*/

    
}
