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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * Dashboard – puerta de entrada
 * Dispatcher por tipo_usuario_id
 */
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/**
 * Dashboards por rol (blindados)
 */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard/medico', [HomeController::class, 'medico'])
        ->middleware('tipo_usuario:1')
        ->name('dashboard.medico');

    Route::get('/dashboard/paciente', [HomeController::class, 'paciente'])
        ->middleware('tipo_usuario:2')
        ->name('dashboard.paciente');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/**
 * Historia clínica
 */
Route::get(
    '/pacientes/{paciente}/historia-clinica',
    [PacienteController::class, 'historiaClinica']
)->name('pacientes.historiaClinica');

Route::get('/diagnosticos/inferir', [DiagnosticoController::class, 'inferirSelector'])
    ->middleware(['auth', 'verified', 'tipo_usuario:1'])
    ->name('diagnosticos.inferirSelector');


/**
 * Rutas propias con policies
 */
Route::post('/tratamientos/{tratamiento}/attach-linea', [TratamientoController::class, 'attach_linea'])
    ->name('tratamientos.attachLinea')
    ->middleware('can:attach_linea,tratamiento');

Route::delete('/tratamientos/{tratamiento}/detach-linea/{medicamento}', [TratamientoController::class, 'detach_linea'])
    ->name('tratamientos.detachLinea')
    ->middleware('can:detach_linea,tratamiento');

Route::post('/diagnosticos/{diagnostico}/attach-sintoma', [DiagnosticoController::class, 'attach_sintoma'])
    ->name('diagnosticos.attachSintoma')
    ->middleware('can:attach_sintoma,diagnostico');

Route::delete('/diagnosticos/{diagnostico}/detach-sintoma/{sintoma}', [DiagnosticoController::class, 'detach_sintoma'])
    ->name('diagnosticos.detachSintoma')
    ->middleware('can:detach_sintoma,diagnostico');

/**
 * Recursos
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

/**
 * Inferencia de diagnóstico
 */
Route::post(
    '/diagnosticos/inferir/{pacienteId}',
    [DiagnosticoController::class, 'inferirDesdeSistema']
)->name('diagnosticos.inferir');

require __DIR__ . '/auth.php';
