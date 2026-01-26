<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\AdminController;
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

use App\Http\Controllers\Paciente\DiagnosticoController as PacienteDiagnosticoController;
use App\Http\Controllers\Paciente\TratamientoController as PacienteTratamientoController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| ZONA PRIVADA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dispatcher dashboard (según tipo_usuario_id)
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Perfil (común)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ADMIN (tipo_usuario_id=3)
    |--------------------------------------------------------------------------
    */
    Route::middleware('tipo_usuario:3')->group(function () {

        Route::get('/dashboard/admin', [AdminController::class, 'index'])
            ->name('dashboard.admin');

        // Backoffice: altas (User + Perfil)
        Route::prefix('admin/usuarios')->name('admin.usuarios.')->group(function () {

            // Landing (opcional) si lo usas
            Route::get('/crear', [AdminController::class, 'create'])
                ->name('create'); // -> view('admin.usuarios.create')

            // Alta paciente
            Route::get('/crear-paciente', [AdminController::class, 'createPaciente'])
                ->name('createPaciente'); // -> view('admin.usuarios.create-paciente')

            Route::post('/paciente', [AdminController::class, 'storePaciente'])
                ->name('storePaciente');

            // Alta médico
            Route::get('/crear-medico', [AdminController::class, 'createMedico'])
                ->name('createMedico'); // -> view('admin.usuarios.create-medico')

            Route::post('/medico', [AdminController::class, 'storeMedico'])
                ->name('storeMedico');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | MÉDICO (tipo_usuario_id=1)
    |--------------------------------------------------------------------------
    */
    Route::middleware('tipo_usuario:1')->group(function () {

        Route::get('/dashboard/medico', [HomeController::class, 'medico'])
            ->name('dashboard.medico');

        // Historia clínica (solo médico)
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

        // Citas (médico)
        Route::resource('citas', CitaController::class)->except(['store']); // store común
        Route::patch('/citas/{cita}/aceptar', [CitaController::class, 'aceptar'])->name('citas.aceptar');
        Route::patch('/citas/{cita}/rechazar', [CitaController::class, 'rechazar'])->name('citas.rechazar');

        // CRUD (catálogo clínico)
        Route::resources([
            'especialidads' => EspecialidadController::class,
            'medicos'       => MedicoController::class,
            'pacientes'     => PacienteController::class,
            'medicamentos'  => MedicamentoController::class,
            'trasplantes'   => TrasplanteController::class,
            'sintomas'      => SintomaController::class,
            'diagnosticos'  => DiagnosticoController::class,
            'tratamientos'  => TratamientoController::class,
            'organos'       => OrganoController::class,
            'estadisticas'  => EstadisticaController::class,
            'pruebas'       => PruebaController::class,
            'reglas'        => ReglaController::class,
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | PACIENTE (tipo_usuario_id=2)
    |--------------------------------------------------------------------------
    */
    Route::middleware('tipo_usuario:2')->group(function () {

        Route::get('/dashboard/paciente', [HomeController::class, 'paciente'])
            ->name('dashboard.paciente');

        // Read-only paciente
        Route::prefix('paciente')->name('paciente.')->group(function () {

            Route::get('/diagnosticos/{diagnostico}', [PacienteDiagnosticoController::class, 'show'])
                ->name('diagnosticos.show');

            Route::get('/tratamientos/{tratamiento}', [PacienteTratamientoController::class, 'show'])
                ->name('tratamientos.show');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | CITA STORE (COMÚN: médico crea / paciente solicita)
    |--------------------------------------------------------------------------
    */
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
});

require __DIR__ . '/auth.php';
