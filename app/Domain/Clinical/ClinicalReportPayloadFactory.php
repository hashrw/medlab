<?php

namespace App\Domain\Clinical;

use App\Models\Diagnostico;

final class ClinicalReportPayloadFactory
{
    public static function fromCasoClinicoAndDiagnostico(
        CasoClinico $casoClinico,
        Diagnostico $diagnostico,
        string $status = 'match'
    ): array {
        return [
            'caso_clinico' => $casoClinico->toArray(),

            'resultado_inferencia' => [
                'status' => $status,
                'diagnostico_id' => (int) $diagnostico->id,
                'tipo_enfermedad' => $diagnostico->tipo_enfermedad,
                'grado_eich' => $diagnostico->grado_eich,
                'estado_injerto' => $diagnostico->estado_injerto,
            ],
        ];
    }
}