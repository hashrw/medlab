<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

class MedicamentoTratamiento extends Pivot
{
    use HasFactory;

    protected $table = 'medicamento_tratamiento';

    protected $casts = [
        'fecha_ini_linea' => 'date:Y-m-d',
        'fecha_fin_linea' => 'date:Y-m-d',
        'fecha_resp_linea' => 'date:Y-m-d',
    ];

    public function getDuracionAttribute(): ?int
    {
        if (!$this->fecha_ini_linea || !$this->fecha_fin_linea) {
            return null;
        }

        $ini = Carbon::parse($this->fecha_ini_linea);
        $fin = Carbon::parse($this->fecha_fin_linea);

        return $fin->diffInDays($ini);
    }

    public function getIsActivaAttribute(): bool
    {
        return is_null($this->fecha_fin_linea);
    }
}