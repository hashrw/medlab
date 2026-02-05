<?php

namespace App\Policies;

use App\Models\Diagnostico;
use App\Models\User;

class DiagnosticoPolicy
{
    /**
     * Fuente de verdad (medico dueño): diagnostico->paciente->medico_id
     * Nota: diagnosticos.paciente_id es nullable, así que hay que proteger nulls.
     */
    private function esDiagnosticoDePacienteAsignadoAMedico(User $user, Diagnostico $diagnostico): bool
    {
        if (!$user->es_medico) {
            return false;
        }

        $medicoId = $user->medico?->id;
        if (!$medicoId) {
            return false;
        }

        $paciente = $diagnostico->paciente; // asume relación belongsTo en Diagnostico
        if (!$paciente) {
            return false;
        }

        return (int) $paciente->medico_id === (int) $medicoId;
    }

    private function esDiagnosticoPropioDePaciente(User $user, Diagnostico $diagnostico): bool
    {
        if (!$user->es_paciente) {
            return false;
        }

        $pacienteId = $user->paciente?->id;
        if (!$pacienteId) {
            return false;
        }

        return (int) $diagnostico->paciente_id === (int) $pacienteId;
    }

    /**
     * Determina si el usuario puede ver cualquier modelo de Diagnostico.
     * Nota: si el paciente no tiene listado en UI, esto no se usa; pero no rompe.
     */
    public function viewAny(User $user): bool
    {
        return $user->es_administrador || $user->es_medico || $user->es_paciente;
    }

    /**
     * Ver un diagnóstico concreto.
     */
    public function view(User $user, Diagnostico $diagnostico): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        if ($this->esDiagnosticoDePacienteAsignadoAMedico($user, $diagnostico)) {
            return true;
        }

        if ($this->esDiagnosticoPropioDePaciente($user, $diagnostico)) {
            return true;
        }

        return false;
    }

    /**
     * Crear diagnósticos.
     * La pertenencia (paciente asignado) se valida al elegir paciente en controller,
     * porque aquí no existe el Diagnostico aún.
     */
    public function create(User $user): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    /**
     * Actualizar un diagnóstico.
     */
    public function update(User $user, Diagnostico $diagnostico): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        return $this->esDiagnosticoDePacienteAsignadoAMedico($user, $diagnostico);
    }

    public function attach_sintoma(User $user, Diagnostico $diagnostico): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        return $this->esDiagnosticoDePacienteAsignadoAMedico($user, $diagnostico);
    }

    public function detach_sintoma(User $user, Diagnostico $diagnostico): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        return $this->esDiagnosticoDePacienteAsignadoAMedico($user, $diagnostico);
    }

    /**
     * Eliminar diagnósticos.
     */
    public function delete(User $user, Diagnostico $diagnostico): bool
    {
        return $user->es_administrador;
    }

    public function restore(User $user, Diagnostico $diagnostico): bool
    {
        return $user->es_administrador;
    }

    public function forceDelete(User $user, Diagnostico $diagnostico): bool
    {
        return $user->es_administrador;
    }
}
