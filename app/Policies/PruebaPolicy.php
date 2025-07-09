<?php

namespace App\Policies;

use App\Models\prueba;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PruebaPolicy
{
    /**
     * Determina si el usuario puede ver cualquier modelo de prueba.
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores y médicos pueden listar pruebas
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede ver un modelo específico de prueba.
     */
    public function view(User $user, Prueba $prueba): bool
    {
        // Solo administradores y médicos pueden ver pruebas
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede crear modelos de prueba.
     */
    public function create(User $user): bool
    {
        // Solo administradores y médicos pueden crear pruebas
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede actualizar un modelo de prueba.
     */
    public function update(User $user, Prueba $prueba): bool
    {
        // Solo administradores y médicos pueden actualizar pruebas
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determina si el usuario puede eliminar un modelo de prueba.
     */
    public function delete(User $user, Prueba $prueba): bool
    {
        // Solo administradores pueden eliminar pruebas
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede restaurar un modelo de prueba.
     */
    public function restore(User $user, Prueba $prueba): bool
    {
        // Solo administradores pueden restaurar pruebas
        return $user->es_administrador;
    }

    /**
     * Determina si el usuario puede eliminar permanentemente un modelo de prueba.
     */
    public function forceDelete(User $user, Prueba $prueba): bool
    {
        // Solo administradores pueden eliminar permanentemente pruebas
        return $user->es_administrador;
    }
}