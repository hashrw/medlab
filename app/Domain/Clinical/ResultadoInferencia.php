<?php

namespace App\Domain\Clinical;

use App\Models\Diagnostico;
use App\Models\ReglaDecision;

final class ResultadoInferencia
{
    public function __construct(
        public readonly string $status, // "match" | "fallback" | "no_match"
        public readonly ?int $diagnostico_id,
        public readonly ?int $regla_decision_id,
        public readonly ?int $fallback_regla_decision_id,
        public readonly array $evidencia_interna, // trazabilidad mínima
        public readonly array $warnings = [],
    ) {
    }

    public static function fromServiceResult(
        ?Diagnostico $diag,
        ?ReglaDecision $fallback,
        CasoClinico $casoClinico
    ): self {
        if ($diag) {
            // Cargamos síntomas del diagnóstico inferido para trazabilidad (si relación existe)
            $diag->loadMissing('sintomas');

            return new self(
                status: 'match',
                diagnostico_id: (int) $diag->id,
                regla_decision_id: (int) $diag->regla_decision_id,
                fallback_regla_decision_id: null,
                evidencia_interna: [
                    'paciente_id' => $casoClinico->pacienteId,
                    'as_of' => $casoClinico->asOf->toIso8601String(),
                    'active_sintoma_ids' => $casoClinico->activeSintomaIds,
                    'active_aliases_canonical' => $casoClinico->activeAliasesCanonical,
                    'organo_score_nih_by_nombre' => $casoClinico->organoScoreNihByNombre,
                    // trazabilidad concreta en el diagnóstico generado:
                    'diagnostico_sintoma_ids' => $diag->sintomas->pluck('id')->map(fn($v) => (int) $v)->values()->all(),
                ],
                warnings: [],
            );
        }

        if ($fallback) {
            return new self(
                status: 'fallback',
                diagnostico_id: null,
                regla_decision_id: null,
                fallback_regla_decision_id: (int) $fallback->id,
                evidencia_interna: [
                    'paciente_id' => $casoClinico->pacienteId,
                    'as_of' => $casoClinico->asOf->toIso8601String(),
                    'active_sintoma_ids' => $casoClinico->activeSintomaIds,
                    'active_aliases_canonical' => $casoClinico->activeAliasesCanonical,
                    'organo_score_nih_by_nombre' => $casoClinico->organoScoreNihByNombre,
                ],
                warnings: ['No hubo match real; se devuelve regla fallback (sin condiciones).'],
            );
        }

        return new self(
            status: 'no_match',
            diagnostico_id: null,
            regla_decision_id: null,
            fallback_regla_decision_id: null,
            evidencia_interna: [
                'paciente_id' => $casoClinico->pacienteId,
                'as_of' => $casoClinico->asOf->toIso8601String(),
                'active_sintoma_ids' => $casoClinico->activeSintomaIds,
                'active_aliases_canonical' => $casoClinico->activeAliasesCanonical,
                'organo_score_nih_by_nombre' => $casoClinico->organoScoreNihByNombre,
            ],
            warnings: ['No hubo match y no existe fallback.'],
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'diagnostico_id' => $this->diagnostico_id,
            'regla_decision_id' => $this->regla_decision_id,
            'fallback_regla_decision_id' => $this->fallback_regla_decision_id,
            'evidencia_interna' => $this->evidencia_interna,
            'warnings' => $this->warnings,
        ];
    }
}
