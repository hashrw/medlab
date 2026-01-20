<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'fecha_hora',
        'medico_id',
        'paciente_id',
        'estado',
        'motivo',
        'motivo_detalle',
        'preferencia_fecha_hora',
        'comentario_medico',
        'respondida_at',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'preferencia_fecha_hora' => 'datetime',
        'respondida_at' => 'datetime',
    ];

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}

