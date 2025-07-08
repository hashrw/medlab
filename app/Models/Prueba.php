<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','tipo_prueba','fecha','resultado','comentario'];

    protected $casts = ['fecha' => 'datetime:Y-m-d'];


}
