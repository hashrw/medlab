<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\User;

class CitaPolicy
{
    private function esCitaPropiaDeMedico(User $user, Cita $cita): bool
    {
        return $user->es_medico && (int) $cita->medico_id === (int) $user->medico->id;
    }

    private function esCitaPropiaDePaciente(User $user, Cita $cita): bool
    {
        return $user->es_paciente && (int) $cita->paciente_id === (int) $user->paciente->id;
    }

    private function esCitaPropia(User $user, Cita $cita): bool
    {
        return $this->esCitaPropiaDeMedico($user, $cita) || $this->esCitaPropiaDePaciente($user, $cita);
    }

    private function medicoPuedeGestionarSolicitud(User $user, Cita $cita): bool
    {
        // Solicitud sin médico asignado todavía
        return $user->es_medico
            && $cita->estado === 'pendiente'
            && is_null($cita->medico_id);
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Cita $cita): bool
    {
        return $user->es_administrador || $this->esCitaPropia($user, $cita) || $this->medicoPuedeGestionarSolicitud($user, $cita);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Cita $cita): bool
    {
        return $user->es_administrador || $this->esCitaPropia($user, $cita) || $this->medicoPuedeGestionarSolicitud($user, $cita);
    }

    public function delete(User $user, Cita $cita): bool
    {
        return $user->es_administrador || $this->esCitaPropia($user, $cita);
    }
}
