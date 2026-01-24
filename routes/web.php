<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\CitaController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\TratamientoController;

use App\Http\Controllers\PacienteController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\TrasplanteController;
use App\Http\Controllers\SintomaController;
use App\Http\Controllers\OrganoController;
use App\Http\Controllers\EstadisticaController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\ReglaController;
use App\Http\Controllers\Auth\RegisteredUserController;


use App\Http\Controllers\Paciente\DiagnosticoController as PacienteDiagnosticoController;
use App\Http\Controllers\Paciente\TratamientoController as PacienteTratamientoController;

Route::middleware('guest')->group(function () {

    // FORMULARIOS
    Route::get('/register/medico', [RegisteredUserController::class, 'create_medico'])
        ->name('register.medico');

    Route::get('/register/paciente', [RegisteredUserController::class, 'create_paciente'])
        ->name('register.paciente');

    // ENVÍO (comparten store)
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register.store');

});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dispatcher dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Perfil (común)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | MÉDICO (tipo_usuario_id=1)
    |--------------------------------------------------------------------------
    */
    Route::middleware('tipo_usuario:1')->group(function () {

        Route::get('/dashboard/medico', [HomeController::class, 'medico'])->name('dashboard.medico');

        // Historia clínica (SOLO médico)
        Route::get('/pacientes/{paciente}/historia-clinica', [PacienteController::class, 'historiaClinica'])
            ->name('pacientes.historiaClinica');

        // Inferencia diagnóstico (SOLO médico)
        Route::get('/diagnosticos/inferir', [DiagnosticoController::class, 'inferirSelector'])
            ->name('diagnosticos.inferirSelector');

        Route::post('/diagnosticos/inferir/{pacienteId}', [DiagnosticoController::class, 'inferirDesdeSistema'])
            ->name('diagnosticos.inferir');

        // Inferencia tratamiento (SOLO médico)
        Route::post('/tratamientos/inferir-desde-diagnostico/{diagnostico}', [TratamientoController::class, 'inferir_desde_diagnostico'])
            ->name('tratamientos.inferirDesdeDiagnostico');

        // Gestión de citas (médico)
        Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
        Route::get('/citas/create', [CitaController::class, 'create'])->name('citas.create');
        Route::get('/citas/{cita}', [CitaController::class, 'show'])->name('citas.show');
        Route::get('/citas/{cita}/edit', [CitaController::class, 'edit'])->name('citas.edit');
        Route::put('/citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
        Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');

        Route::patch('/citas/{cita}/aceptar', [CitaController::class, 'aceptar'])->name('citas.aceptar');
        Route::patch('/citas/{cita}/rechazar', [CitaController::class, 'rechazar'])->name('citas.rechazar');

        // CRUD (médico)
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
            'estadisticas' => EstadisticaController::class,
            'pruebas' => PruebaController::class,
            'reglas' => ReglaController::class,
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | PACIENTE (tipo_usuario_id=2)
    |--------------------------------------------------------------------------
    */
    Route::middleware('tipo_usuario:2')->group(function () {

        Route::get('/dashboard/paciente', [HomeController::class, 'paciente'])->name('dashboard.paciente');

        // Read-only paciente (con namespace de nombres paciente.*)
        Route::prefix('paciente')->name('paciente.')->group(function () {
            Route::get('/diagnosticos/{diagnostico}', [PacienteDiagnosticoController::class, 'show'])
                ->name('diagnosticos.show');

            Route::get('/tratamientos/{tratamiento}', [PacienteTratamientoController::class, 'show'])
                ->name('tratamientos.show');

            // Si quieres que el paciente tenga bandeja/listado propio:
            // Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
            // Route::get('/citas/{cita}', [CitaController::class, 'show'])->name('citas.show');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | CITA STORE (común)
    |--------------------------------------------------------------------------
    | Mantengo esto con name citas.store porque tu partial del paciente ya lo usa.
    | Dentro del controller validas por rol (FormRequest) y el middleware auth ya existe.
    */
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
});

require __DIR__ . '/auth.php';
