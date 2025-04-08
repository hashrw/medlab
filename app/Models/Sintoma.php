<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sintoma extends Model
{
    use HasFactory;
    
    protected $fillable = ['sintoma','manif_clinica','organo_id'];

    public function organo(){
        return $this->belongsTo(Organo::class);
    }

    public function diagnosticos(): BelongsToMany{
        return $this->belongsToMany(Diagnostico::class)->using(DiagnosticoSintoma::class)->withPivot('fecha_diagnostico', 'score_nih');

    }
    
}


