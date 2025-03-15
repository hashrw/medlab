<?php

namespace App\Policies;
use App\Models\Enfermedad;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EnfermedadPolicy
{
    /**
     * Determina si el usuario puede ver cualquier modelo de Enfermedad.
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores y médicos pueden listar enfermedades
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede ver un modelo específico de Enfermedad.
     */
    public function view(User $user, Enfermedad $enfermedad): bool
    {
        // Solo administradores y médicos pueden ver enfermedades
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede crear modelos de Enfermedad.
     */
    public function create(User $user): bool
    {
        // Solo administradores y médicos pueden crear enfermedades
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede actualizar un modelo de Enfermedad.
     */
    public function update(User $user, Enfermedad $enfermedad): bool
    {
        // Solo administradores y médicos pueden actualizar enfermedades
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede eliminar un modelo de Enfermedad.
     */
    public function delete(User $user, Enfermedad $enfermedad): bool
    {
        // Solo administradores pueden eliminar enfermedades
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede restaurar un modelo de Enfermedad.
     */
    public function restore(User $user, Enfermedad $enfermedad): bool
    {
        // Solo administradores pueden restaurar enfermedades
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede eliminar permanentemente un modelo de Enfermedad.
     */
    public function forceDelete(User $user, Enfermedad $enfermedad): bool
    {
        // Solo administradores pueden eliminar permanentemente enfermedades
        return $user->es_administrador;
    }
}
