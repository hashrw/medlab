<?php

namespace App\Policies;

use App\Models\Paciente;
use App\Models\User;

class PacientePolicy
{
    /**
     * Listado de pacientes
     * - Admin: sí
     * - Médico: sí (pero el filtrado "mis pacientes" se aplica en el controller)
     * - Paciente: no (normalmente no lista pacientes)
     */
    public function viewAny(User $user): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Ver ficha de paciente
     * - Admin: sí
     * - Médico: SOLO si el paciente está asignado (paciente.medico_id = medico.id)
     * - Paciente: SOLO si es su propia ficha (read-only)
     */
    public function view(User $user, Paciente $paciente): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        if ($user->es_medico) {
            $medicoId = $user->medico?->id;

            if (!$medicoId) {
                return false;
            }

            return (int) $paciente->medico_id === (int) $medicoId;
        }

        if ($user->es_paciente) {
            $pacienteId = $user->paciente?->id;

            if (!$pacienteId) {
                return false;
            }

            return (int) $paciente->id === (int) $pacienteId;
        }

        return false;
    }

    /**
     * Crear paciente
     * - SOLO ADMIN
     */
    public function create(User $user): bool
    {
        return $user->es_administrador;
    }

    /**
     * Actualizar paciente
     * - SOLO ADMIN (si quieres permitir edición clínica por médico, esto se cambia)
     */
    public function update(User $user, Paciente $paciente): bool
    {

        if ($user->es_administrador) {
            return true;
        }

        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            if (!$medicoId) {
                return false;
            }
            return (int) $paciente->medico_id === (int) $medicoId;
        }
        return false;
    }

    /**
     * Eliminar paciente
     * - SOLO ADMIN
     */
    public function delete(User $user, Paciente $paciente): bool
    {
        return $user->es_administrador;
    }

    public function restore(User $user, Paciente $paciente): bool
    {
        return $user->es_administrador;
    }

    public function forceDelete(User $user, Paciente $paciente): bool
    {
        return $user->es_administrador;
    }
}
