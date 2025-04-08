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
        return $this->belongsToMany(Tratamiento::class)->using(MedicamentoTratamiento::class)->withPivot('fecha_ini_linea','duracion_linea','duracion_total','fecha_fin_linea','fecha_resp_linea','observaciones','tomas');
                  
    }
 
 
    // Fecha de inicio de la línea de tratamiento
    // Duración de la línea de tratamiento
   // Duración total del tratamiento
     // Fecha de fin de la línea de tratamiento
    // Fecha de respuesta a la línea de tratamiento
  // Observaciones de la línea de tratamiento
 // Número de tomas)

}
