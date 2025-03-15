<?php

namespace App\Policies;

use App\Models\Diagnostico;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DiagnosticoPolicy
{
    /**
     * Determina si el usuario puede ver cualquier modelo de Diagnostico.
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores y médicos pueden listar diagnósticos
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede ver un modelo específico de Diagnostico.
     */
    public function view(User $user, Diagnostico $diagnostico): bool
    {
        // Solo administradores y médicos pueden ver diagnósticos
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede crear modelos de Diagnostico.
     */
    public function create(User $user): bool
    {
        // Solo administradores y médicos pueden crear diagnósticos
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede actualizar un modelo de Diagnostico.
     */
    public function update(User $user, Diagnostico $diagnostico): bool
    {
        // Solo administradores y médicos pueden actualizar diagnósticos
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede eliminar un modelo de Diagnostico.
     */
    public function delete(User $user, Diagnostico $diagnostico): bool
    {
        // Solo administradores pueden eliminar diagnósticos
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede restaurar un modelo de Diagnostico.
     */
    public function restore(User $user, Diagnostico $diagnostico): bool
    {
        // Solo administradores pueden restaurar diagnósticos
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede eliminar permanentemente un modelo de Diagnostico.
     */
    public function forceDelete(User $user, Diagnostico $diagnostico): bool
    {
        // Solo administradores pueden eliminar permanentemente diagnósticos
        return $user->es_administrador;
    }
}