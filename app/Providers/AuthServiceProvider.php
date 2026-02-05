<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Cita;
use App\Models\Diagnostico;
use App\Models\Especialidad;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Prueba;
use App\Models\Tratamiento;

use App\Policies\CitaPolicy;
use App\Policies\DiagnosticoPolicy;
use App\Policies\EspecialidadPolicy;
use App\Policies\MedicamentoPolicy;
use App\Policies\MedicoPolicy;
use App\Policies\PacientePolicy;
use App\Policies\PruebaPolicy;
use App\Policies\TratamientoPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Cita::class => CitaPolicy::class,
        Especialidad::class => EspecialidadPolicy::class,
        Medico::class => MedicoPolicy::class,
        Medicamento::class => MedicamentoPolicy::class,
        Tratamiento::class => TratamientoPolicy::class,

        // P0: control "mis pacientes"
        Paciente::class => PacientePolicy::class,
        Diagnostico::class => DiagnosticoPolicy::class,
        Prueba::class => PruebaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // CRITICO: sin esto, no se registran los mappings anteriores.
        $this->registerPolicies();
    }
}
