<?php

namespace App\Policies;

use App\Models\Sintoma;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SintomaPolicy
{
    /**
     * Determina si el usuario puede ver cualquier modelo de Sintoma.
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores y médicos pueden listar síntomas
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede ver un modelo específico de Sintoma.
     */
    public function view(User $user, Sintoma $sintoma): bool
    {
        // Solo administradores y médicos pueden ver síntomas
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede crear modelos de Sintoma.
     */
    public function create(User $user): bool
    {
        // Solo administradores y médicos pueden crear síntomas
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede actualizar un modelo de Sintoma.
     */
    public function update(User $user, Sintoma $sintoma): bool
    {
        // Solo administradores y médicos pueden actualizar síntomas
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede eliminar un modelo de Sintoma.
     */
    public function delete(User $user, Sintoma $sintoma): bool
    {
        // Solo administradores pueden eliminar síntomas
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede restaurar un modelo de Sintoma.
     */
    public function restore(User $user, Sintoma $sintoma): bool
    {
        // Solo administradores pueden restaurar síntomas
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede eliminar permanentemente un modelo de Sintoma.
     */
    public function forceDelete(User $user, Sintoma $sintoma): bool
    {
        // Solo administradores pueden eliminar permanentemente síntomas
        return $user->es_administrador;
    }
}