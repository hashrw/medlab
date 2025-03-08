<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    use HasFactory;
    
    protected $fillable = ['tipo_trasplante','nombre_enfermedad','fecha_trasplante','origen_trasplante','identidad_hla',
    'tipo_acondicionamiento','seropositividad_donante','seropositividad_receptor','paciente_id'];

    protected $casts = [
        'fecha_trasplante' => 'date:Y-m-d',
    ];

    public function diagnosticos(){
        return $this->hasMany(Diagnostico::class);
    }
    
}
