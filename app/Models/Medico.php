<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $fillable = ['residente', 'especialidad_id','user_id'];

    /*protected $casts = [
        'vacunado' => 'boolean',
        'fecha_contratacion' => 'datetime:Y-m-d'
    ];*/

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function especialidad(){
        return $this->belongsTo(Especialidad::class);
    }

    public function pacientes(){
        return $this->hasMany(Paciente::class);
    }

    public function diagnosticos(){
        return $this->hasMany(Diagnostico::class);
    }

    public function tratamientos(){
        return $this->hasMany(Tratamiento::class);
    }

    public function citas(){
        return $this->hasMany(Cita::class);
    }

    
    /*public function pacientes(){
        return $this->hasManyThrough(Paciente::class, Cita::class);
    }*/

    /*public function getDiasContratadoAttribute(){
        return Carbon::now()->diffInDays($this->fecha_contratacion);
    }*/
}
