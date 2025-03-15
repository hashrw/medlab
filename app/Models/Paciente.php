<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = ['nuhsa','fecha_nacimiento','peso','altura','sexo','user_id','enfermedad_id','tratamiento_id'];

    protected $casts = [
        'fecha_nacimiento' => 'datetime:Y-m-d',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function enfermedads()
    {
        return $this->belongsToMany(Enfermedad::class, 'paciente_enfermedad');
    }

    public function tratamientos(){
        return $this->hasMany(Tratamiento::class);
    }

    // Accesor para calcular la edad
    public function getEdadAttribute()
    {
        return now()->diffInYears($this->fecha_nacimiento);
    }

    // Validación de la fecha de nacimiento
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($paciente) {
            if (now()->diffInYears($paciente->fecha_nacimiento) < 5) {
                throw new \Exception('La fecha de nacimiento no puede ser inferior a 5 años.');
            }
        });
    }

   /* public function citas(){
        return $this->hasMany(Cita::class);
    }

    public function medicos(){
        return $this->hasManyThrough(Medico::class, Cita::class);
    }

    public function getMedicamentosActualesAttribute(){
        $medicamentos_actuales = collect([]);
        foreach ($this->citas as $cita) {
            $medicamentos_actuales->merge($cita->medicamentos()->wherePivot('inicio','<=', Carbon::now())->wherePivot('fin','>=', Carbon::now())->get());
            /* Alternativa
            if($cita->medicamentos()->wherePivot('inicio','<=', Carbon::now())->wherePivot('fin','>=', Carbon::now())->exists()){
                $medicamentos_actuales->merge($cita->medicamentos()->wherePivot('inicio','<=', Carbon::now())->wherePivot('fin','>=', Carbon::now())->get());
            }
            
        }
        return $medicamentos_actuales;
    }*/

}
