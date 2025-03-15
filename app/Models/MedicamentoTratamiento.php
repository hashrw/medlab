<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MedicamentoTratamiento extends Model
{
    use HasFactory;

    protected $fillable = ['fecha_ini_linea','duracion_linea','duracion_total','fecha_fin_linea','fecha_resp_linea',
    'observaciones','tratamiento_id','medicamento_id'];

    protected $casts = [
        'fecha_ini_linea' => 'datetime:Y-m-d',
        'fecha_fin_linea' => 'date:Y-m-d',
        'fecha_resp_linea' => 'date:Y-m-d',

    ];

     // Relación con el modelo Tratamiento
     public function tratamiento(): BelongsTo
     {
         return $this->belongsTo(Tratamiento::class);
     }
 
     // Relación con el modelo Medicamento
     public function medicamentos(): BelongsToMany
     {
         return $this->belongsToMany(Medicamento::class)->withPivot('tomas', 'observaciones');
     }
 
     // Accesor para calcular la duración de la línea de tratamiento
     public function getDuracionAttribute()
     {
         if ($this->fecha_ini_linea && $this->fecha_fin_linea) {
             return $this->fecha_fin_linea->diffInDays($this->fecha_ini_linea);
         }
 
         return 0;
     }
}
