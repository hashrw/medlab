<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicamentoAlias extends Model
{
    use HasFactory;

    protected $table = 'medicamento_aliases';

    protected $fillable = [
        'medicamento_id',
        'alias',
        'tipo', // 'canonical' | 'synonym' (string)
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }
}
