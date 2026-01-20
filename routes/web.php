<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\TrasplanteController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\OrganoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\SintomaController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\EstadisticaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReglaController;
use App\Http\Controllers\Paciente\DiagnosticoController as PacienteDiagnosticoController;
use App\Http\Controllers\Paciente\TratamientoController as PacienteTratamientoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Todo lo funcional va autenticado + verificado
 */
Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * Dashboard – puerta de entrada (dispatcher por tipo_usuario_id)
     */
    Route::get('/dashboard', [HomeController::class, 'index'])
        ->name('dashboard');

    /**
     * Perfil (común)
     */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /**
     * ---------------------------
     * ROL: MÉDICO (tipo_usuario_id=1)
     * ---------------------------
     */
    Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

        Route::get('/dashboard/medico', [HomeController::class, 'medico'])
            ->middleware('tipo_usuario:1')
            ->name('dashboard.medico');

        Route::get('/dashboard/paciente', [HomeController::class, 'paciente'])
            ->middleware('tipo_usuario:2')
            ->name('dashboard.paciente');

        // Perfil (común)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        /**
         * PACIENTE (tipo_usuario_id=2)
         */
        Route::middleware('tipo_usuario:2')->group(function () {

            Route::prefix('paciente')->name('paciente.')->group(function () {
                Route::get('/diagnosticos/{diagnostico}', [PacienteDiagnosticoController::class, 'show'])
                    ->name('diagnosticos.show');

                Route::get('/tratamientos/{tratamiento}', [PacienteTratamientoController::class, 'show'])
                    ->name('tratamientos.show');
            });

            // Solicitud de cita desde el dashboard del paciente
            // (mantiene route('citas.store') como ya tienes en el partial)
            Route::post('/citas', [CitaController::class, 'store'])
                ->name('citas.store');
        });

        /**
         * MEDICO (tipo_usuario_id=1)
         */
        Route::middleware('tipo_usuario:1')->group(function () {

            // Historia clínica (si esto es solo para médico, déjalo aquí)
            Route::get('/pacientes/{paciente}/historia-clinica', [PacienteController::class, 'historiaClinica'])
                ->name('pacientes.historiaClinica');

            // Inferencia diagnóstico (solo médico)
            Route::get('/diagnosticos/inferir', [DiagnosticoController::class, 'inferirSelector'])
                ->name('diagnosticos.inferirSelector');

            Route::post('/diagnosticos/inferir/{pacienteId}', [DiagnosticoController::class, 'inferirDesdeSistema'])
                ->name('diagnosticos.inferir');

            // Inferencia tratamiento (solo médico)
            Route::post('/tratamientos/inferir-desde-diagnostico/{diagnostico}', [TratamientoController::class, 'inferir_desde_diagnostico'])
                ->name('tratamientos.inferirDesdeDiagnostico');

            // Gestión citas (médico)
            Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
            Route::get('/citas/create', [CitaController::class, 'create'])->name('citas.create');
            Route::post('/citas', [CitaController::class, 'store'])->name('citas.store'); // también para médico
            Route::get('/citas/{cita}', [CitaController::class, 'show'])->name('citas.show');
            Route::get('/citas/{cita}/edit', [CitaController::class, 'edit'])->name('citas.edit');
            Route::put('/citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
            Route::patch('/citas/{cita}', [CitaController::class, 'update']);
            Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');

            // Tratamientos: acciones especiales con policy
            Route::post('/tratamientos/{tratamiento}/attach-linea', [TratamientoController::class, 'attach_medicamento'])
                ->name('tratamientos.attachLinea')
                ->middleware('can:update,tratamiento');

            Route::delete('/tratamientos/{tratamiento}/detach-linea/{medicamento}', [TratamientoController::class, 'detach_medicamento'])
                ->name('tratamientos.detachLinea')
                ->middleware('can:update,tratamiento');

            Route::patch('/tratamientos/{tratamiento}/cerrar-linea', [TratamientoController::class, 'cerrar_linea'])
                ->name('tratamientos.cerrarLinea')
                ->middleware('can:update,tratamiento');

            // Diagnósticos: acciones especiales con abilities
            Route::post('/diagnosticos/{diagnostico}/attach-sintoma', [DiagnosticoController::class, 'attach_sintoma'])
                ->name('diagnosticos.attachSintoma')
                ->middleware('can:attach_sintoma,diagnostico');

            Route::delete('/diagnosticos/{diagnostico}/detach-sintoma/{sintoma}', [DiagnosticoController::class, 'detach_sintoma'])
                ->name('diagnosticos.detachSintoma')
                ->middleware('can:detach_sintoma,diagnostico');

            Route::patch('/citas/{cita}/aceptar', [CitaController::class, 'aceptar'])->name('citas.aceptar');
            Route::patch('/citas/{cita}/rechazar', [CitaController::class, 'rechazar'])->name('citas.rechazar');

            // CRUD del médico (lo que ya tenías en resources)
            Route::resources([
                'especialidads' => EspecialidadController::class,
                'medicos' => MedicoController::class,
                'pacientes' => PacienteController::class,
                'medicamentos' => MedicamentoController::class,
                'trasplantes' => TrasplanteController::class,
                'sintomas' => SintomaController::class,
                'diagnosticos' => DiagnosticoController::class,
                'tratamientos' => TratamientoController::class,
                'organos' => OrganoController::class,
                'estadisticas' => EstadisticaController::class, // ojo si esta clase se llama distinto
                'pruebas' => PruebaController::class,
                'reglas' => ReglaController::class,
            ]);
        });
    });


    /**
     * ---------------------------
     * ROL: PACIENTE (tipo_usuario_id=2)
     * ---------------------------
     */
    Route::middleware(['tipo_usuario:2'])->group(function () {

        Route::get('/dashboard/paciente', [HomeController::class, 'paciente'])
            ->name('dashboard.paciente');

        /**
         * Vistas PACIENTE (read-only)
         */
        Route::prefix('paciente')->name('paciente.')->group(function () {
            Route::get('/diagnosticos/{diagnostico}', [PacienteDiagnosticoController::class, 'show'])
                ->name('diagnosticos.show');

            Route::get('/tratamientos/{tratamiento}', [PacienteTratamientoController::class, 'show'])
                ->name('tratamientos.show');
        });

        /**
         * Citas: el paciente necesita al menos crear (store) y ver las suyas (index/show)
         * Opciones:
         * - Si mantienes CitaController como resource general de médico, aquí limitamos rutas.
         */
        Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
        Route::get('/citas/{cita}', [CitaController::class, 'show'])->name('citas.show');
        Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
    });

    /**
     * Historia clínica (común pero NO pública)
     * Si quieres que SOLO médico acceda, muévela al grupo de médico.
     * Si paciente debe ver la suya, crea otra ruta /paciente/historia.
     */
    Route::get('/pacientes/{paciente}/historia-clinica', [PacienteController::class, 'historiaClinica'])
        ->name('pacientes.historiaClinica');
});

require __DIR__ . '/auth.php';
