<?php

namespace App\Policies;

use App\Models\Trasplante;
use App\Models\User;

class TrasplantePolicy
{
    private function esTrasplanteDePacienteAsignadoAMedico(User $user, Trasplante $trasplante): bool
    {
        if (!$user->es_medico) {
            return false;
        }

        $medicoId = $user->medico?->id;
        if (!$medicoId) {
            return false;
        }

        $paciente = $trasplante->paciente; // requiere belongsTo en Trasplante
        if (!$paciente) {
            return false;
        }

        return (int) $paciente->medico_id === (int) $medicoId;
    }

    private function esTrasplantePropioDePaciente(User $user, Trasplante $trasplante): bool
    {
        if (!$user->es_paciente) {
            return false;
        }

        $pacienteId = $user->paciente?->id;
        if (!$pacienteId) {
            return false;
        }

        return (int) $trasplante->paciente_id === (int) $pacienteId;
    }

    public function viewAny(User $user): bool
    {
        // Si mañana añades listado paciente, ya está soportado.
        return $user->es_administrador || $user->es_medico || $user->es_paciente;
    }

    public function view(User $user, Trasplante $trasplante): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        if ($this->esTrasplanteDePacienteAsignadoAMedico($user, $trasplante)) {
            return true;
        }

        if ($this->esTrasplantePropioDePaciente($user, $trasplante)) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        // admin o médico. Paciente no, salvo que lo abras explícitamente.
        return $user->es_administrador || $user->es_medico;
    }

    public function update(User $user, Trasplante $trasplante): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        return $this->esTrasplanteDePacienteAsignadoAMedico($user, $trasplante);
    }

    public function delete(User $user, Trasplante $trasplante): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    public function restore(User $user, Trasplante $trasplante): bool
    {
        return $user->es_administrador;
    }

    public function forceDelete(User $user, Trasplante $trasplante): bool
    {
        return $user->es_administrador;
    }
}
