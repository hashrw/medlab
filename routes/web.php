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
use App\Http\Controllers\InformeClinicoController;
use App\Http\Controllers\Auth\RegisteredUserController;

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
    Route::middleware(['auth', 'tipo_usuario:3'])->group(function () {

        Route::get('/dashboard/admin', [AdminController::class, 'index'])
            ->name('dashboard.admin');

        // Backoffice: altas (User + Perfil)
        Route::prefix('admin/usuarios')->name('admin.usuarios.')->group(function () {

            Route::get('/crear', [AdminController::class, 'create'])
                ->name('create');

            // Alta paciente
            Route::get('/crear-paciente', [AdminController::class, 'createPaciente'])
                ->name('createPaciente');

            Route::post('/paciente', [AdminController::class, 'storePaciente'])
                ->name('storePaciente');

            // Alta médico
            Route::get('/crear-medico', [AdminController::class, 'createMedico'])
                ->name('createMedico');

            Route::post('/medico', [AdminController::class, 'storeMedico'])
                ->name('storeMedico');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | MÉDICO (tipo_usuario_id=1)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'tipo_usuario:1'])->group(function () {

        Route::get('/dashboard/medico', [HomeController::class, 'medico'])
            ->name('dashboard.medico');

        // Historia clínica (solo médico)
        Route::get('/pacientes/{paciente}/historia-clinica', [PacienteController::class, 'historiaClinica'])
            ->name('pacientes.historiaClinica');

        // Inferencia diagnóstico (solo médico)
        Route::get('/diagnosticos/inferir', [DiagnosticoController::class, 'inferirSelector'])
            ->name('diagnosticos.inferirSelector');

        Route::post('/diagnosticos/{diagnostico}/lineas', [DiagnosticoController::class, 'attach_sintoma'])
            ->name('diagnosticos.attachSintoma');

        Route::post('/diagnosticos/inferir/{pacienteId}', [DiagnosticoController::class, 'inferirDesdeSistema'])
            ->name('diagnosticos.inferir');

        // Inferencia tratamiento (solo médico)
        Route::post('/tratamientos/inferir-desde-diagnostico/{diagnostico}', [TratamientoController::class, 'inferir_desde_diagnostico'])
            ->name('tratamientos.inferirDesdeDiagnostico');

        /*
        |----------------------------------------------------------------------
        | Líneas de tratamiento (solo médico)
        |----------------------------------------------------------------------
        | Estas rutas son necesarias porque la vista tratamientos/edit.blade.php
        | llama a route('tratamientos.cerrarLinea', ...).
        */
        Route::post('/tratamientos/{tratamiento}/lineas', [TratamientoController::class, 'attach_linea'])
            ->name('tratamientos.attachLinea');

        Route::delete('/tratamientos/{tratamiento}/lineas/{linea}', [TratamientoController::class, 'detach_linea'])
            ->name('tratamientos.detachLinea');

        Route::patch('/tratamientos/{tratamiento}/cerrar-linea', [TratamientoController::class, 'cerrar_linea'])
            ->name('tratamientos.cerrarLinea');

        // Citas (médico) -> store va aparte (COMÚN)
        Route::resource('citas', CitaController::class)->except(['store']);
        Route::patch('/citas/{cita}/aceptar', [CitaController::class, 'aceptar'])->name('citas.aceptar');
        Route::patch('/citas/{cita}/rechazar', [CitaController::class, 'rechazar'])->name('citas.rechazar');

        /*
        |--------------------------------------------------------------------------
        | CRUD (catálogo clínico) bajo rol MÉDICO
        |--------------------------------------------------------------------------
        | NOTA (recordatorio pendiente de revisión):
        | Ahora mismo este bloque expone resources bajo rol MÉDICO para catálogos que,
        | en un diseño típico, no deberían ser CRUD completos por médico (p.ej. medicos,
        | especialidades, etc.). Aunque las Policies bloqueen acciones, la ruta existe.
        |
        | Pendiente: decidir qué resources deben quedarse aquí, cuáles mover a ADMIN,
        | y cuáles dejar read-only según el flujo clínico real.
        */
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

        Route::get('/pacientes/{paciente}/sintomas/crear', [PacienteController::class, 'createSintomas'])
            ->name('pacientes.sintomas.create');

        Route::post('/pacientes/{paciente}/sintomas', [PacienteController::class, 'storeSintomas'])
            ->name('pacientes.sintomas.store');

        Route::post('/pacientes/{paciente}/organos-score', [PacienteController::class, 'storeOrganoScores'])
            ->name('pacientes.organosScore.store');

        Route::prefix('pacientes/{paciente}')->group(function () {
            Route::get('trasplantes/create', [TrasplanteController::class, 'create'])->name('pacientes.trasplantes.create');
            Route::post('trasplantes', [TrasplanteController::class, 'store'])->name('pacientes.trasplantes.store');

            Route::get('pruebas/create', [PruebaController::class, 'create'])->name('pacientes.pruebas.create');
            Route::post('pruebas', [PruebaController::class, 'store'])->name('pacientes.pruebas.store');
        });

        // infección por diagnóstico
        Route::get('diagnosticos/{diagnostico}/infeccion', [DiagnosticoController::class, 'edit'])->name('diagnosticos.infeccion.edit');
        Route::put('diagnosticos/{diagnostico}/infeccion', [DiagnosticoController::class, 'update'])->name('diagnosticos.infeccion.update');

        // TRASPLANTES (nested)
        Route::get('/pacientes/{paciente}/trasplantes/create', [TrasplanteController::class, 'create'])
            ->name('pacientes.trasplantes.create');

        Route::post('/pacientes/{paciente}/trasplantes', [TrasplanteController::class, 'store'])
            ->name('pacientes.trasplantes.store');

        Route::post(
            '/diagnosticos/{diagnostico}/evidencia',
            [DiagnosticoController::class, 'generarEvidencia']
        )
            ->name('diagnosticos.evidencia');


        Route::post('/informes-clinicos/{informeClinico}/cancelar', [InformeClinicoController::class, 'cancelar'])
            ->name('informes.cancelar');

        Route::get('/informes-clinicos/{informeClinico}/estado', [InformeClinicoController::class, 'estado'])
            ->name('informes.estado');

        Route::get('/informes-clinicos/notificaciones', function () {
            $informes = \App\Models\InformeClinico::whereIn('status', ['completed', 'fallback'])
                ->whereNull('notified_at')
                ->latest()
                ->take(3)
                ->get();

            $items = $informes->map(function ($inf) {
                return [
                    'id' => $inf->id,
                    'diagnostico_id' => $inf->diagnostico_id,
                    'status' => $inf->status,
                    'message' => $inf->status === 'completed'
                        ? 'Informe clínico generado'
                        : 'Informe clínico generado parcialmente',
                    'url' => route('diagnosticos.show', $inf->diagnostico_id),
                ];
            });

            if ($informes->isNotEmpty()) {
                \App\Models\InformeClinico::whereIn('id', $informes->pluck('id'))
                    ->update(['notified_at' => now()]);
            }

            return response()->json([
                'items' => $items,
            ]);
        })->name('informes-clinicos.notificaciones');

        Route::post('/informes-clinicos/{informeClinico}/validar', [InformeClinicoController::class, 'validar'])
            ->name('informes.validar');


    });




    /*
    |--------------------------------------------------------------------------
    | PACIENTE (tipo_usuario_id=2)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'tipo_usuario:2'])->group(function () {

        Route::get('/dashboard/paciente', [HomeController::class, 'paciente'])
            ->name('dashboard.paciente');
        Route::prefix('paciente')->name('paciente.')->group(function () {

            Route::get('/diagnosticos/{diagnostico}', [PacienteDiagnosticoController::class, 'show'])
                ->name('diagnosticos.show');

            Route::get('/tratamientos/{tratamiento}', [PacienteTratamientoController::class, 'show'])
                ->name('tratamientos.show');

        });

    });

    /*
    |--------------------------------------------------------------------------
    | CITA store (COMÚN: médico crea / paciente solicita)
    |--------------------------------------------------------------------------
    | Se protege por middleware tipo_usuario:1,2 (confirmas que soporta listas).
    | La autorización fina sigue siendo responsabilidad de Policy + FormRequest.
    */
    Route::post('/citas', [CitaController::class, 'store'])
        ->middleware('tipo_usuario:1,2')
        ->name('citas.store');
});

require __DIR__ . '/auth.php';
