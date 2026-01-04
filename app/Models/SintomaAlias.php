<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SintomaAlias extends Model
{
    protected $table = 'sintoma_aliases';

    protected $fillable = [
        'sintoma_id',
        'alias',
        'tipo',
        'nota',
    ];

    public function sintoma()
    {
        return $this->belongsTo(Sintoma::class);
    }
}
