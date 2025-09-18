<?php

namespace App\Policies;

use App\Models\Tratamiento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TratamientoPolicy
{
    // Métodos privados para verificar propiedad
    private function esTratamientoPropioDeMedico(User $user, Tratamiento $tratamiento): bool
    {
        return $user->es_medico && $tratamiento->medico_id == $user->medico->id;
    }

    private function esTratamientoPropioDePaciente(User $user, Tratamiento $tratamiento): bool
    {
        return $user->es_paciente && $tratamiento->paciente_id == $user->paciente->id;
    }

    private function esTratamientoPropio(User $user, Tratamiento $tratamiento): bool
    {
        return $this->esTratamientoPropioDeMedico($user, $tratamiento) || $this->esTratamientoPropioDePaciente($user, $tratamiento);
    }

    /**
     * Determina si el usuario puede ver cualquier modelo de Tratamiento.
     */
    public function viewAny(User $user): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede ver un modelo específico de Tratamiento.
     */
    public function view(User $user, Tratamiento $tratamiento): bool
    {
        return $user->es_administrador || $user->es_medico || $this->esTratamientoPropio($user, $tratamiento);
    }

    /**
     * Determina si el usuario puede crear modelos de Tratamiento.
     */
    public function create(User $user): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede actualizar un modelo de Tratamiento.
     */
    public function update(User $user, Tratamiento $tratamiento): bool
    {
        return $user->es_administrador || $this->esTratamientoPropioDeMedico($user, $tratamiento);
    }

    /**
     * Determina si el usuario puede eliminar un modelo de Tratamiento.
     */
    public function delete(User $user, Tratamiento $tratamiento): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede restaurar un modelo de Tratamiento.
     */
    public function restore(User $user, Tratamiento $tratamiento): bool
    {
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede eliminar permanentemente un modelo de Tratamiento.
     */
    public function forceDelete(User $user, Tratamiento $tratamiento): bool
    {
        return $user->es_administrador;
    }
}
