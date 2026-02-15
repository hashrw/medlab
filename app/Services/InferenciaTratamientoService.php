<?php

namespace App\Services;

use App\Models\Diagnostico;
use App\Models\ReglaTratamiento;
use App\Models\Tratamiento;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InferenciaTratamientoService
{
    /**
     * Genera un tratamiento inferido a partir de un diagnóstico inferido.
     *
     * Alcance: EICH Aguda.
     * - tipo_enfermedad: 'aguda'
     * - 1ª línea (solo esteroide sistémico + esteroides tópicos)
     * - NO infiere IC
     *
     * Reglas:
     * - Máximo 1 tratamiento inferido "activo" por paciente:
     *   - Si existe uno previo, se cierra (fecha_fin_linea hoy si estaba null)
     * - Si el diagnóstico es no concluyente, no genera tratamiento.
     */
    public function inferirDesdeDiagnostico(Diagnostico $diagnostico, int $medicoId): ?Tratamiento
    {
        $pacienteId = (int) ($diagnostico->paciente_id ?? 0);
        if ($pacienteId <= 0) {
            throw new \LogicException("Diagnóstico {$diagnostico->id} no tiene paciente_id.");
        }

        $tipoEnf = (string) ($diagnostico->tipo_enfermedad ?? '');
        if ($tipoEnf === '') {
            throw new \LogicException("Diagnóstico {$diagnostico->id} no tiene tipo_enfermedad.");
        }

        $grado = (string) ($diagnostico->grado_eich ?? '');
        if ($grado === '' || $grado === 'no_concluyente') {
            return null;
        }

        // Resolver regla aplicable (prioridad ascendente)
        $regla = $this->resolverRegla($diagnostico);

        $acciones = (array) ($regla->acciones ?? []);
        $tData = $acciones['tratamiento'] ?? null;

        // Fallback o regla vacía
        if (!$tData || empty($tData['tratamiento'])) {
            return null;
        }

        return DB::transaction(function () use ($diagnostico, $medicoId, $pacienteId, $regla, $acciones, $tData) {

            // 1) Cerrar tratamiento inferido previo (si existe)
            $prev = $this->ultimoTratamientoInferido($pacienteId);
            if ($prev) {
                $this->cerrarLineasAbiertas($prev);
            }

            // 2) Crear tratamiento nuevo
            $tratamiento = Tratamiento::create([
                'tratamiento' => $tData['tratamiento'],
                'fecha_asignacion' => now()->toDateString(),
                'descripcion' => $tData['descripcion'] ?? null,
                'paciente_id' => $pacienteId,
                'medico_id' => $medicoId,
                'diagnostico_id' => $diagnostico->id,
            ]);

            // 3) Adjuntar líneas
            $lineas = $acciones['lineas'] ?? [];
            foreach ($lineas as $linea) {
                $this->attachLinea($tratamiento, (array) $linea);
            }

            // 4) Recalcular duracion_total (opcional, útil para tu accesor)
            $this->actualizarDuracionTotalPivot($tratamiento);

            return $tratamiento;
        });
    }

    private function resolverRegla(Diagnostico $diagnostico): ReglaTratamiento
    {
        $reglas = ReglaTratamiento::query()
            ->where('activo', true)
            ->orderBy('prioridad')
            ->get();

        foreach ($reglas as $r) {
            if ($this->cumpleCondiciones((array) ($r->condiciones ?? []), $diagnostico)) {
                return $r;
            }
        }

        // Si no hay match, devolvemos regla vacía (equivalente a fallback)
        return new ReglaTratamiento([
            'acciones' => [
                'tratamiento' => ['tratamiento' => null, 'descripcion' => null],
                'lineas' => [],
            ],
        ]);
    }

    /**
     * Condiciones soportadas v1:
     * - tipo_enfermedad ('aguda'|'cronica')
     * - grado_eich ('leve'|'moderada'|'severa'|...)
     */
    private function cumpleCondiciones(array $cond, Diagnostico $d): bool
    {
        if (empty($cond)) {
            return true;
        }

        if (isset($cond['tipo_enfermedad']) && ($d->tipo_enfermedad ?? null) !== $cond['tipo_enfermedad']) {
            return false;
        }

        if (isset($cond['grado_eich']) && ($d->grado_eich ?? null) !== $cond['grado_eich']) {
            return false;
        }

        return true;
    }

    /**
     * Definición operativa de "inferido":
     * - tratamientos con diagnostico_id NOT NULL.
     */
    private function ultimoTratamientoInferido(int $pacienteId): ?Tratamiento
    {
        return Tratamiento::query()
            ->where('paciente_id', $pacienteId)
            ->whereNotNull('diagnostico_id')
            ->latest('id')
            ->first();
    }

    private function cerrarLineasAbiertas(Tratamiento $tratamiento): void
    {
        $tratamiento->loadMissing('lineasTratamiento');

        $hoy = now()->toDateString();

        foreach ($tratamiento->lineasTratamiento as $med) {
            if (!$med->pivot->fecha_fin_linea) {
                $tratamiento->lineasTratamiento()->updateExistingPivot(
                    $med->id,
                    ['fecha_fin_linea' => $hoy]
                );
            }
        }
    }

    private function attachLinea(Tratamiento $tratamiento, array $linea): void
    {
        $alias = (string) ($linea['medicamento_alias'] ?? '');
        if ($alias === '') {
            throw new \RuntimeException("ReglaTratamiento sin medicamento_alias en una línea.");
        }

        $medId = $this->medicamentoIdPorAlias($alias);

        $ini = $this->resolverFecha((string) ($linea['fecha_ini_linea'] ?? 'AUTO_TODAY'));

        $dur = array_key_exists('duracion_linea', $linea) ? (int) $linea['duracion_linea'] : null;

        $fin = $linea['fecha_fin_linea'] ?? null;

        // Opción A: NO autocerrar por duración.
        // Solo cerramos si la regla trae fecha_fin_linea explícita (no null) o un flag de autocierre.
        $autoCerrar = (bool) ($linea['cerrar_automaticamente'] ?? false);

        if ($autoCerrar && !$fin && $dur) {
            $fin = Carbon::parse($ini)->addDays($dur)->toDateString();
        }

        $tratamiento->lineasTratamiento()->attach($medId, [
            'fecha_ini_linea' => $ini,
            'duracion_linea' => $dur,
            'duracion_total' => null,
            'fecha_fin_linea' => $fin,
            'fecha_resp_linea' => $linea['fecha_resp_linea'] ?? null,
            'observaciones' => $linea['observaciones'] ?? null,
            'tomas' => $linea['tomas'] ?? null,
        ]);
    }

    private function medicamentoIdPorAlias(string $alias): int
    {
        $id = DB::table('medicamento_aliases')
            ->where('alias', $alias)
            ->value('medicamento_id');

        if (!$id) {
            throw new \RuntimeException("MedicamentoAlias no encontrado: '{$alias}'");
        }

        return (int) $id;
    }

    private function resolverFecha(string $token): string
    {
        return match ($token) {
            'AUTO_TODAY' => now()->toDateString(),
            'AUTO_AFTER_14D' => now()->addDays(14)->toDateString(),
            default => Carbon::parse($token)->toDateString(),
        };
    }

    /**
     * Rellena duracion_total en las filas pivot del tratamiento, usando:
     * - inicio mínimo
     * - fin máximo
     */
    private function actualizarDuracionTotalPivot(Tratamiento $tratamiento): void
    {
        $tratamiento->loadMissing('lineasTratamiento');

        if ($tratamiento->lineasTratamiento->isEmpty()) {
            return;
        }

        $iniMin = null;
        $finMax = null;

        foreach ($tratamiento->lineasTratamiento as $med) {
            if ($med->pivot->fecha_ini_linea) {
                $ini = Carbon::parse($med->pivot->fecha_ini_linea);
                $iniMin = $iniMin ? $iniMin->min($ini) : $ini;
            }
            if ($med->pivot->fecha_fin_linea) {
                $fin = Carbon::parse($med->pivot->fecha_fin_linea);
                $finMax = $finMax ? $finMax->max($fin) : $fin;
            }
        }

        if (!$iniMin || !$finMax) {
            return;
        }

        $total = $finMax->diffInDays($iniMin);

        foreach ($tratamiento->lineasTratamiento as $med) {
            $tratamiento->lineasTratamiento()->updateExistingPivot(
                $med->id,
                ['duracion_total' => $total]
            );
        }
    }
}
