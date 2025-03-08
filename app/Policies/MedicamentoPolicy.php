<?php

namespace App\Policies;

use App\Models\Medicamento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MedicamentoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Medicamento $medicamento): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Medicamento $medicamento): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Medicamento $medicamento): bool
    {
        return $user->es_administrador || $user->es_medico;
    }
}
