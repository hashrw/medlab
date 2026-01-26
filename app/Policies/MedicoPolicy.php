<?php

namespace App\Policies;

use App\Models\Medico;
use App\Models\User;

class MedicoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->es_administrador;
    }

    public function view(User $user, Medico $medico): bool
    {
        return $user->es_administrador;
    }

    /**
     * Crear médico
     * - SOLO ADMIN
     */
    public function create(User $user): bool
    {
        return $user->es_administrador;
    }

    /**
     * Actualizar médico
     * - SOLO ADMIN
     * (el médico NO se auto-edita su perfil clínico)
     */
    public function update(User $user, Medico $medico): bool
    {
        return $user->es_administrador;
    }

    /**
     * Eliminar médico
     * - SOLO ADMIN
     */
    public function delete(User $user, Medico $medico): bool
    {
        return $user->es_administrador;
    }
}
