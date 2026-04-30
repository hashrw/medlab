<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformeClinico extends Model
{
    use HasFactory;

    protected $fillable = [
        'diagnostico_id',
        'paciente_id',
        'status',
        'clinical_report',
        'traceability',
        'llm_used',
        'llm_model',
        'fallback_reason',
        'generated_at',
        'started_at',
        'finished_at',
        'error_message',
    ];

    protected $casts = [
        'clinical_report' => 'array',
        'traceability' => 'array',
        'llm_used' => 'boolean',
        'generated_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    protected $table = 'informes_clinicos';

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

}
