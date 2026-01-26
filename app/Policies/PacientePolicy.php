<?php

namespace App\Policies;

use App\Models\Paciente;
use App\Models\User;

class PacientePolicy
{
    /**
     * Listado de pacientes
     * - Admin
     * - Médico
     */
    public function viewAny(User $user): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Ver ficha de paciente
     * - Admin
     * - Médico
     * - (Paciente propio se controla fuera)
     */
    public function view(User $user, Paciente $paciente): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Crear paciente
     * - SOLO ADMIN
     */
    public function create(User $user): bool
    {
        return $user->es_administrador;
    }

    /**
     * Actualizar paciente
     * - SOLO ADMIN
     */
    public function update(User $user, Paciente $paciente): bool
    {
        return $user->es_administrador;
    }

    /**
     * Eliminar paciente
     * - SOLO ADMIN
     */
    public function delete(User $user, Paciente $paciente): bool
    {
        return $user->es_administrador;
    }

    public function restore(User $user, Paciente $paciente): bool
    {
        return $user->es_administrador;
    }

    public function forceDelete(User $user, Paciente $paciente): bool
    {
        return $user->es_administrador;
    }
}
