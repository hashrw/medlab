<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Paciente extends Model
{
    protected $fillable = ['nuhsa', 'fecha_nacimiento', 'peso', 'altura', 'sexo', 'user_id'];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];


    protected $guarded = ['nuhsa']; // Protege el campo cie10 contra ediciones


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //RELACION CON TRASPLANTE

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function trasplantes()
    {
        return $this->hasMany(Trasplante::class);
    }


    public function tratamientos()
    {
        return $this->belongsToMany(Tratamiento::class)->using(PacienteTratamiento::class)->withPivot('paciente_id', 'tratamiento_id');
    }

    public function organos()
    {
        return $this->belongsToMany(Organo::class, 'organo_paciente')
            ->withPivot('score_nih', 'fecha_evaluacion', 'comentario', 'sintomas_asociados')
            ->withTimestamps();
    }

    public function sintomas()
    {
        return $this->belongsToMany(Sintoma::class, 'paciente_sintoma')
            ->withPivot('fecha_observacion', 'activo', 'fuente')
            ->wherePivot('activo', true)
            ->withTimestamps();
    }

    public function diagnosticos(): BelongsToMany
    {
        return $this->belongsToMany(Diagnostico::class, 'diagnostico_paciente')->withTimestamps();
    }


    //helper última ficha trasplante paciente
    public function getFichaTrasplanteActual()
    {
        return $this->enfermedades()->orderByDesc('fecha_trasplante')->first();
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

    public function getICMCategoriaAttribute()
    {

        $imc = $this->imc;

        if (!$imc) {
            return null;
        }

        if ($imc < 18.5) {
            return 'Bajo Peso';
        }

        if ($imc < 25) {
            return 'Normal';
        }

        if ($imc < 30) {
            return 'Sobrepeso';
        }

        if ($imc < 35) {
            return 'Obesidad II';
        }
        if ($imc < 40) {
            return 'Obesidad III';
        }
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
