<?php

namespace App\Policies;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PacientePolicy
{
    /**
     * Determina si el usuario puede ver cualquier modelo de Paciente.
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores y médicos pueden listar pacientes
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede ver un modelo específico de Paciente.
     */
    public function view(User $user, Paciente $paciente): bool
    {
        // Solo administradores y médicos pueden ver pacientes
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede crear modelos de Paciente.
     */
    public function create(User $user): bool
    {
        // Solo administradores y médicos pueden crear pacientes
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede actualizar un modelo de Paciente.
     */
    public function update(User $user, Paciente $paciente): bool
    {
        // Solo administradores y médicos pueden actualizar pacientes
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede eliminar un modelo de Paciente.
     */
    public function delete(User $user, Paciente $paciente): bool
    {
        // Solo administradores pueden eliminar pacientes
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede restaurar un modelo de Paciente.
     */
    public function restore(User $user, Paciente $paciente): bool
    {
        // Solo administradores pueden restaurar pacientes
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede eliminar permanentemente un modelo de Paciente.
     */
    public function forceDelete(User $user, Paciente $paciente): bool
    {
        // Solo administradores pueden eliminar permanentemente pacientes
        return $user->es_administrador;
    }
}