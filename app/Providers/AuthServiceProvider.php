<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Cita;
use App\Models\Especialidad;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Policies\CitaPolicy;
use App\Policies\EspecialidadPolicy;
use App\Policies\MedicamentoPolicy;
use App\Policies\MedicoPolicy;
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
        Medicamento::class => MedicamentoPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
