<?php

namespace App\Domain\Clinical;

use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

final class CasoClinicoFactory
{
    public static function fromPaciente(Paciente $paciente, ?Carbon $asOf = null): CasoClinico
    {
        $asOf = $asOf ?? Carbon::now();

        // Síntomas activos (ids). SIN DEPENDER de $paciente->sintomas cargado en memoria.
        $activeSintomaIds = $paciente->sintomas()
            ->wherePivot('activo', true)
            ->pluck('sintomas.id')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        // Aliases activos (strings)
        $activeAliasesCanonical = [];
        if (!empty($activeSintomaIds)) {
            $activeAliasesCanonical = DB::table('sintoma_aliases')
                ->whereIn('sintoma_id', $activeSintomaIds)
                ->where('tipo', 'canonical')
                ->pluck('alias')
                ->map(fn($a) => (string) $a)
                ->unique()
                ->values()
                ->all();
        }

        // Scores NIH por órgano (clave: nombre del órgano)
        $paciente->loadMissing('organos');
        $organoScoreNihByNombre = [];
        foreach ($paciente->organos as $organo) {
            $nombre = (string) ($organo->nombre ?? '');
            if ($nombre === '') {
                continue;
            }
            $score = $organo->pivot->score_nih ?? null;
            $organoScoreNihByNombre[$nombre] = is_numeric($score) ? (int) $score : null;
        }

        return new CasoClinico(
            pacienteId: (int) $paciente->id,
            activeSintomaIds: $activeSintomaIds,
            activeAliasesCanonical: $activeAliasesCanonical,
            organoScoreNihByNombre: $organoScoreNihByNombre,
            asOf: $asOf
        );
    }
}
