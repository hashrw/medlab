<?php

namespace App\Policies;

use App\Models\Tratamiento;
use App\Models\User;

class TratamientoPolicy
{
    /**
     * Fuente de verdad (medico dueño): tratamiento->paciente->medico_id
     * Nota: proteger nulls (relaciones inexistentes).
     */
    private function esTratamientoDePacienteAsignadoAMedico(User $user, Tratamiento $tratamiento): bool
    {
        if (!$user->es_medico) {
            return false;
        }

        $medicoId = $user->medico?->id;
        if (!$medicoId) {
            return false;
        }

        $paciente = $tratamiento->paciente; // asume belongsTo en Tratamiento
        if (!$paciente) {
            return false;
        }

        return (int) $paciente->medico_id === (int) $medicoId;
    }

    private function esTratamientoPropioDePaciente(User $user, Tratamiento $tratamiento): bool
    {
        if (!$user->es_paciente) {
            return false;
        }

        $pacienteId = $user->paciente?->id;
        if (!$pacienteId) {
            return false;
        }

        return (int) $tratamiento->paciente_id === (int) $pacienteId;
    }

    /**
     * Listar tratamientos.
     * Si el paciente tiene listado en UI, permitimos viewAny también al paciente,
     * pero la query en controller debe ir filtrada (P0.5).
     */
    public function viewAny(User $user): bool
    {
        return $user->es_administrador || $user->es_medico || $user->es_paciente;
    }

    public function view(User $user, Tratamiento $tratamiento): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        if ($this->esTratamientoDePacienteAsignadoAMedico($user, $tratamiento)) {
            return true;
        }

        if ($this->esTratamientoPropioDePaciente($user, $tratamiento)) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    public function update(User $user, Tratamiento $tratamiento): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        return $this->esTratamientoDePacienteAsignadoAMedico($user, $tratamiento);
    }

    public function delete(User $user, Tratamiento $tratamiento): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        // Recomendación P0: médico NO global
        return $this->esTratamientoDePacienteAsignadoAMedico($user, $tratamiento);
    }

    public function restore(User $user, Tratamiento $tratamiento): bool
    {
        return $user->es_administrador;
    }

    public function forceDelete(User $user, Tratamiento $tratamiento): bool
    {
        return $user->es_administrador;
    }
}
