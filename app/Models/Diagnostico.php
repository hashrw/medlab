<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Diagnostico extends Model
{
    use HasFactory;

    protected $fillable = ['paciente_id',
                'enfermedad_id',
                'medico_id', // Relación con Médico
                'dias_desde_trasplante',
                'tipo_enfermedad' ,
                'estado_enfermedad' ,
                'comienzo_cronica' ,
                'escala_karnofsky' ,
                'estado_injerto' ,
                'tipo_infeccion',
                'f_hospitalizacion',
                'f_electromiografia',
                'f_eval_injerto',
                'f_medulograma',
                'f_espirometria',
                'f_esplenectomia',
                'hipoalbuminemia', 
                'observaciones'];

    protected $casts = [
        'f_hospitalizacion' => 'datetime:Y-m-d H:i',
        'f_electromiografia' => 'datetime:Y-m-d H:i',
        'f_eval_injerto' => 'datetime:Y-m-d H:i',
        'f_esplenectomia' => 'datetime:Y-m-d H:i',
        'f_medulograma' => 'datetime:Y-m-d H:i',
        'f_espirometria' => 'datetime:Y-m-d H:i'
                    
        ];

    protected $guarded = ['cie10']; // Protege el campo cie10 contra ediciones

     public function enfermedad(): BelongsTo{
        return $this->belongsTo(Enfermedad::class);
     }
    
     // Relación con el modelo Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    // Relación con el modelo Sintoma (muchos a muchos)
    public function sintomas(): BelongsToMany{
        return $this->belongsToMany(Sintoma::class)->withPivot('fecha_diagnostico', 'score_nih');
    }

}
