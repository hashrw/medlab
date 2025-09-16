<?php

namespace App\Policies;

use App\Models\Trasplante;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TrasplantePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores y médicos pueden listar trasplantes
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Trasplante $trasplante): bool
    {
        // Solo administradores y médicos pueden ver trasplantes
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo administradores y médicos pueden crear trasplantes
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Trasplante $trasplante): bool
    {
        // Solo administradores y médicos pueden actualizar trasplantes
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Trasplante $trasplante): bool
    {
        // administradores y medicos pueden eliminar trasplantes
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Trasplante $trasplante): bool
    {
        // administradores y medicos pueden restaurar trasplantes
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Trasplante $trasplante): bool
    {
        // administradores y medicos pueden eliminar permanentemente trasplantes
        return $user->es_administrador || $user->es_medico;
    }
}
