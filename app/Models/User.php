<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'apellidos',
        'telefono',
        'password',
        'tipo_usuario_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $guarded = ['email']; // Protege el tipo de usuario y el email contra ediciones

    public function medico()
    {
        return $this->hasOne(Medico::class);
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }

    public function getTipoUsuarioIdAttribute(){
        if ($this->medico()->exists()){
            return 1;
        }
        elseif($this->paciente()->exists()){
            return 2;
        }
        else{
            return 3;
        }
    }

    public function getTipoUsuarioAttribute(){
        $tipos_usuario = [1 => trans('Médico'), 2 => trans('Paciente'), 3 => trans('Administrador')];
        return $tipos_usuario[$this->tipo_usuario_id];
    }

   public function getEsPacienteAttribute(){
        return $this->tipo_usuario_id == 2;
    }

    public function getEsMedicoAttribute(){
        return $this->tipo_usuario_id == 1;
    }

    public function getEsAdministradorAttribute(){
        return $this->tipo_usuario_id == 3;
    }
}
