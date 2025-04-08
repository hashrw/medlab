<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CitaPolicy
{

    private function esCitaPropiaDeMedico(User $user, Cita $cita): bool
    {
        return $user->es_medico && $cita->medico_id == $user->medico->id;
    }

    private function esCitaPropiaDePaciente(User $user, Cita $cita): bool
    {
        return $user->es_paciente && $cita->paciente_id == $user->paciente->id;
    }

    private function esCitaPropia(User $user, Cita $cita): bool
    {
        return $this->esCitaPropiaDeMedico($user, $cita) || $this->esCitaPropiaDePaciente($user, $cita);
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cita $cita): bool
    {
        return $user->es_administrador || $this->esCitaPropia($user, $cita);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cita $cita): bool
    {
        return $user->es_administrador || $this->esCitaPropia($user, $cita);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cita $cita): bool
    {
        return $user->es_administrador || $this->esCitaPropia($user, $cita);
    }

    public function attach_medicamento(User $user, Cita $cita)
    {
        return $user->es_administrador || $this->esCitaPropiaDeMedico($user, $cita);
    }

    public function detach_medicamento(User $user, Cita $cita)
    {
        return $user->es_administrador || $this->esCitaPropiaDeMedico($user, $cita);
    }
}
