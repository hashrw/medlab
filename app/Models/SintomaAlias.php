<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintomaAlias extends Model
{
    use HasFactory;

    protected $fillable = [
        'sintoma_id',
        'alias',
        'nota',
    ];

    public function sintoma()
    {
        return $this->belongsTo(Sintoma::class);
    }
}
        