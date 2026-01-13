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
     * Dashboards por rol (blindados)
     */
    Route::get('/dashboard/medico', [HomeController::class, 'medico'])
        ->middleware('tipo_usuario:1')
        ->name('dashboard.medico');

    Route::get('/dashboard/paciente', [HomeController::class, 'paciente'])
        ->middleware('tipo_usuario:2')
        ->name('dashboard.paciente');

    /**
     * Perfil
     */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /**
     * Historia clínica (antes era pública: ahora NO)
     */
    Route::get(
        '/pacientes/{paciente}/historia-clinica',
        [PacienteController::class, 'historiaClinica']
    )->name('pacientes.historiaClinica');

    /**
     * Diagnóstico – inferencia (selector y ejecución) SOLO médico
     */
    Route::middleware(['tipo_usuario:1'])->group(function () {

        Route::get('/diagnosticos/inferir', [DiagnosticoController::class, 'inferirSelector'])
            ->name('diagnosticos.inferirSelector');

        Route::post(
            '/diagnosticos/inferir/{pacienteId}',
            [DiagnosticoController::class, 'inferirDesdeSistema']
        )->name('diagnosticos.inferir');

        /**
         * Inferencia de tratamiento (desde diagnóstico) SOLO médico
         */
        Route::post(
            '/tratamientos/inferir-desde-diagnostico/{diagnostico}',
            [TratamientoController::class, 'inferir_desde_diagnostico']
        )->name('tratamientos.inferirDesdeDiagnostico');
    });

    /**
     * Rutas propias (tratamientos) con policy update
     */
    Route::post('/tratamientos/{tratamiento}/attach-linea', [TratamientoController::class, 'attach_medicamento'])
        ->name('tratamientos.attachLinea')
        ->middleware('can:update,tratamiento');

    Route::delete('/tratamientos/{tratamiento}/detach-linea/{medicamento}', [TratamientoController::class, 'detach_medicamento'])
        ->name('tratamientos.detachLinea')
        ->middleware('can:update,tratamiento');

    /**
     * NUEVA: Cerrar línea de tratamiento (pivot fecha_fin_linea)
     */
    Route::patch('/tratamientos/{tratamiento}/cerrar-linea', [TratamientoController::class, 'cerrar_linea'])
        ->name('tratamientos.cerrarLinea')
        ->middleware('can:update,tratamiento');

    /**
     * Rutas propias (diagnósticos) con abilities específicas
     */
    Route::post('/diagnosticos/{diagnostico}/attach-sintoma', [DiagnosticoController::class, 'attach_sintoma'])
        ->name('diagnosticos.attachSintoma')
        ->middleware('can:attach_sintoma,diagnostico');

    Route::delete('/diagnosticos/{diagnostico}/detach-sintoma/{sintoma}', [DiagnosticoController::class, 'detach_sintoma'])
        ->name('diagnosticos.detachSintoma')
        ->middleware('can:detach_sintoma,diagnostico');

    /**
     * Recursos (CRUD) también blindados
     */
    Route::resources([
        'citas' => CitaController::class,
        'especialidads' => EspecialidadController::class,
        'medicos' => MedicoController::class,
        'pacientes' => PacienteController::class,
        'medicamentos' => MedicamentoController::class,
        'trasplantes' => TrasplanteController::class,
        'sintomas' => SintomaController::class,
        'diagnosticos' => DiagnosticoController::class,
        'tratamientos' => TratamientoController::class,
        'organos' => OrganoController::class,
        'estadisticas' => EstadisticaController::class,
        'pruebas' => PruebaController::class,
        'reglas' => ReglaController::class,
    ]);
});

require __DIR__ . '/auth.php';
