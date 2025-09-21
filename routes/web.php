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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    return match (true) {
        $user->tipo_usuario_id === 1 => redirect()->route('dashboard.medico'),
        $user->tipo_usuario_id === 2 => redirect()->route('dashboard.paciente'),
        default => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard/medico', function () {
    return view('dashboard.medico');
})->middleware(['auth'])->name('dashboard.medico');

Route::get('/dashboard/paciente', function () {
    return view('dashboard.paciente');
})->middleware(['auth'])->name('dashboard.paciente');

/* Rutas propias */
// Pongo las rutas de citas antes de las de recurso para que no matchee antes un show de citas, por ejemplo
// Fíjate en que a estas rutas les añado un middleware de tipo can para que primero autorice al usuario a realizar la acción llamando a los métodos attach_medicamento y detach_medicamento de CitaPolicy.
// Podríamos haberlo hecho directamente dentro de los métodos en el controlador con $this->authorize('attach_medicamento', $cita); y $this->authorize('detach_medicamento', $cita);, respectivamente

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

Route::resources([
    'citas' => CitaController::class,
    'especialidads' => EspecialidadController::class,
    'medicos' => MedicoController::class, // Rutas para médicos (RF-01, RF-02, RF-23)
    'pacientes' => PacienteController::class, // Rutas para pacientes (RF-03, RF-04, RF-05, RF-22)
    'medicamentos' => MedicamentoController::class, // Rutas para medicamentos (RF-19, RF-20, RF-21, RF-26)
    'trasplantes' => TrasplanteController::class, // Rutas para ficha trasplantes (RF-06, RF-07, RF-08, RF-09)
    'sintomas' => SintomaController::class, // Rutas para síntomas (RF-10, RF-11, RF-12, RF-24)
    'diagnosticos' => DiagnosticoController::class, // Rutas para diagnósticos (RF-13, RF-14, RF-15)
    'tratamientos' => TratamientoController::class, // Rutas para tratamientos (RF-16, RF-17, RF-18, RF-25)
    'organos' => OrganoController::class, // Rutas para organos
    'estadisticas' => EstadisticaController::class,
    'pruebas' => PruebaController::class,
    'reglas' => ReglaController::class
]);

Route::get('/diagnosticos/inferir/{pacienteId}', [DiagnosticoController::class, 'inferirDesdeSistema'])
    ->name('diagnosticos.inferir');



/* Ejemplo: Estas 7 rutas son las que se crean, por ejemplo, con la ruta de tipo recurso de citas. 
Lo mismo con el resto de controladores de tipo recurso
Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
Route::get('/citas/create', [CitaController::class, 'create'])->name('citas.create');
Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
Route::get('/citas/{cita}', [CitaController::class, 'show'])->name('citas.show');
Route::get('/citas/{cita}/edit', [CitaController::class, 'edit'])->name('citas.edit'); 
Route::put('/citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
*/

require __DIR__ . '/auth.php';
