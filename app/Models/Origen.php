<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Origen extends Model
{
    use HasFactory;

    protected $fillable = ['origen'];

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class, 'origen_id');
    }
}
