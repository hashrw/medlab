<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Trasplante extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_trasplante',
        'fecha_trasplante',
        'origen_trasplante',
        'identidad_hla',
        'tipo_acondicionamiento',
        'seropositividad_donante',
        'seropositividad_receptor',
        'paciente_id',
    ];

    protected $casts = [
        'fecha_trasplante' => 'date:Y-m-d',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function getDiasDesdeTrasplanteAttribute(): ?int
    {
        if (!$this->fecha_trasplante) {
            return null;
        }

        return Carbon::parse($this->fecha_trasplante)->diffInDays(now());
    }

}
