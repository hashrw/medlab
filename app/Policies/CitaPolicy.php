<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\User;

class CitaPolicy
{
    private function esCitaPropiaDeMedico(User $user, Cita $cita): bool
    {
        $medicoId = $user->medico?->id;
        if (!$user->es_medico || !$medicoId) {
            return false;
        }

        return (int) $cita->medico_id === (int) $medicoId;
    }

    private function esCitaPropiaDePaciente(User $user, Cita $cita): bool
    {
        $pacienteId = $user->paciente?->id;
        if (!$user->es_paciente || !$pacienteId) {
            return false;
        }

        return (int) $cita->paciente_id === (int) $pacienteId;
    }

    private function esPacienteAsignadoAMedico(User $user, Cita $cita): bool
    {
        if (!$user->es_medico) {
            return false;
        }

        $medicoId = $user->medico?->id;
        if (!$medicoId) {
            return false;
        }

        // Requiere relación belongsTo paciente en Cita (normal en tu modelo).
        $paciente = $cita->paciente;
        if (!$paciente) {
            return false;
        }

        return (int) $paciente->medico_id === (int) $medicoId;
    }

    private function medicoPuedeGestionarCita(User $user, Cita $cita): bool
    {
        // Si está asignada al médico: ok.
        if ($this->esCitaPropiaDeMedico($user, $cita)) {
            return true;
        }

        // Si no tiene medico_id (caso raro), permitir solo si el paciente está asignado a este médico.
        if ($user->es_medico && is_null($cita->medico_id) && $this->esPacienteAsignadoAMedico($user, $cita)) {
            return true;
        }

        return false;
    }

    public function viewAny(User $user): bool
    {
        return $user->es_administrador || $user->es_medico || $user->es_paciente;
    }

    public function view(User $user, Cita $cita): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        if ($this->esCitaPropiaDePaciente($user, $cita)) {
            return true;
        }

        if ($this->medicoPuedeGestionarCita($user, $cita)) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Paciente puede solicitar, médico puede crear, admin puede todo.
        return $user->es_administrador || $user->es_medico || $user->es_paciente;
    }

    public function update(User $user, Cita $cita): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        // Paciente: solo su cita (y según tu lógica real, probablemente solo si pendiente)
        if ($this->esCitaPropiaDePaciente($user, $cita)) {
            return true;
        }

        // Médico: solo si es su cita o del paciente asignado a él
        return $this->medicoPuedeGestionarCita($user, $cita);
    }

    public function delete(User $user, Cita $cita): bool
    {
        if ($user->es_administrador) {
            return true;
        }

        // Médico/paciente: solo las propias
        return $this->esCitaPropiaDePaciente($user, $cita) || $this->medicoPuedeGestionarCita($user, $cita);
    }
}
