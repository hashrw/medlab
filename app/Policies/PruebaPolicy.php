<?php

namespace App\Policies;

use App\Models\Prueba;
use App\Models\User;

class PruebaPolicy
{
    private function esPruebaDePacienteAsignadoAMedico(User $user, Prueba $prueba): bool
    {
        if (!$user->es_medico) {
            return false;
        }

        $medicoId = $user->medico?->id;
        if (!$medicoId) {
            return false;
        }

        $paciente = $prueba->paciente; // asume relación belongsTo en Prueba
        if (!$paciente) {
            return false;
        }

        return (int) $paciente->medico_id === (int) $medicoId;
    }

    private function esPruebaPropiaDePaciente(User $user, Prueba $prueba): bool
    {
        if (!$user->es_paciente) {
            return false;
        }

        $pacienteId = $user->paciente?->id;
        if (!$pacienteId) {
            return false;
        }

        return (int) $prueba->paciente_id === (int) $pacienteId;
    }

    /**
     * Listar pruebas (si existe vista/listado).
     * La seguridad real también exige filtrar queries en controllers (P0.5).
     */
    public function viewAny(User $user): bool
    {
        return $user->es_administrador || $user->es_medico || $user->es_paciente;
    }

    public function view(User $user, Prueba $prueba): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        if ($this->esPruebaDePacienteAsignadoAMedico($user, $prueba)) {
            return true;
        }

        if ($this->esPruebaPropiaDePaciente($user, $prueba)) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Pertenencia se valida en controller (paciente asignado) al crear.
        //return $user->es_administrador || $user->es_medico;
        return true;
    }

    public function update(User $user, Prueba $prueba): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        return $this->esPruebaDePacienteAsignadoAMedico($user, $prueba);
    }

    public function delete(User $user, Prueba $prueba): bool
    {
        return $user->es_administrador || $user->es_medico;
    }

    public function restore(User $user, Prueba $prueba): bool
    {
        return $user->es_administrador;
    }

    public function forceDelete(User $user, Prueba $prueba): bool
    {
        return $user->es_administrador;
    }
}
