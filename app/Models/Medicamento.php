<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicamento extends Model
{
    protected $fillable = ['nombre', 'miligramos'];

    public function lineasTratamiento()
    {
        return $this->belongsToMany(Tratamiento::class, 'medicamento_tratamiento', 'medicamento_id', 'tratamiento_id')
            ->using(MedicamentoTratamiento::class)
            ->withPivot('fecha_ini_linea', 'duracion_linea', 'duracion_total', 'fecha_fin_linea', 'fecha_resp_linea', 'observaciones', 'tomas')
            ->withTimestamps();
    }


    public function aliases()
    {
        return $this->hasMany(MedicamentoAlias::class);
    }
    // Fecha de inicio de la línea de tratamiento
    // Duración de la línea de tratamiento
    // Duración total del tratamiento
    // Fecha de fin de la línea de tratamiento
    // Fecha de respuesta a la línea de tratamiento
    // Observaciones de la línea de tratamiento
    // Número de tomas)

    public static function idPorAlias(string $alias): int
    {
        $id = \DB::table('medicamento_aliases')
            ->where('alias', $alias)
            ->value('medicamento_id');

        if (!$id) {
            throw new \RuntimeException("MedicamentoAlias no encontrado: '{$alias}'");
        }

        return (int) $id;
    }


}
