<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'apellidos',
        'telefono',
        'password',
        'tipo_usuario_id',
        'paciente_id',          // vinculación opcional a paciente clínico
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $guarded = ['email'];

    /*--------------------------------------------------------------
     | RELACIÓN: Usuario → Paciente clínico
     --------------------------------------------------------------*/
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /*--------------------------------------------------------------
     | RELACIÓN: Médico
     --------------------------------------------------------------*/
    public function medico()
    {
        return $this->hasOne(Medico::class);
    }

    /*--------------------------------------------------------------
     | ROLES (NO SE TOCAN)
     --------------------------------------------------------------*/
    public function getTipoUsuarioIdAttribute()
    {
        return $this->attributes['tipo_usuario_id'];
    }

    public function getTipoUsuarioAttribute()
    {
        return [
            1 => __('Médico'),
            2 => __('Paciente'),
            3 => __('Administrador'),
        ][$this->tipo_usuario_id] ?? 'Desconocido';
    }

    public function getEsPacienteAttribute()
    {
        return $this->tipo_usuario_id == 2;
    }

    public function getEsMedicoAttribute()
    {
        return $this->tipo_usuario_id == 1;
    }

    public function getEsAdministradorAttribute()
    {
        return $this->tipo_usuario_id == 3;
    }
}
