<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetEvidencia extends Model
{
    protected $fillable = [
        'diagnostico_id',
        'status',
        'error_message',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}
