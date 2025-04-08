<?php

namespace App\Policies;

use App\Models\Organo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganoPolicy
{
    /**
     * Determina si el usuario puede ver cualquier modelo de Organo.
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores y médicos pueden listar órganos
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede ver un modelo específico de Organo.
     */
    public function view(User $user, Organo $organo): bool
    {
        // Solo administradores y médicos pueden ver órganos
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede crear modelos de Organo.
     */
    public function create(User $user): bool
    {
        // Solo administradores y médicos pueden crear órganos
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede actualizar un modelo de Organo.
     */
    public function update(User $user, Organo $organo): bool
    {
        // Solo administradores y médicos pueden actualizar órganos
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede eliminar un modelo de Organo.
     */
    public function delete(User $user, Organo $organo): bool
    {
        // Solo administradores pueden eliminar órganos
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede restaurar un modelo de Organo.
     */
    public function restore(User $user, Organo $organo): bool
    {
        // Solo administradores pueden restaurar órganos
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede eliminar permanentemente un modelo de Organo.
     */
    public function forceDelete(User $user, Organo $organo): bool
    {
        // Solo administradores pueden eliminar permanentemente órganos
        return $user->es_administrador;
    }
}