<?php

namespace App\Domain\Clinical;

use Carbon\Carbon;

final class CasoClinico
{
    /**
     * @param array<int> $activeSintomaIds
     * @param array<string> $activeAliasesCanonical
     * @param array<string, int|null> $organoScoreNihByNombre
     */
    public function __construct(
        public readonly int $pacienteId,
        public readonly array $activeSintomaIds,
        public readonly array $activeAliasesCanonical,
        public readonly array $organoScoreNihByNombre,
        public readonly Carbon $asOf
    ) {
    }

    public function hasActiveSymptoms(): bool
    {
        return !empty($this->activeSintomaIds) || !empty($this->activeAliasesCanonical);
    }

    public function scoreForOrganoNombre(string $organoNombre): ?int
    {
        return $this->organoScoreNihByNombre[$organoNombre] ?? null;
    }

    public function toArray(): array
    {
        return [
            'paciente_id' => $this->pacienteId,
            'as_of' => $this->asOf->toIso8601String(),
            'active_sintoma_ids' => $this->activeSintomaIds,
            'active_aliases_canonical' => $this->activeAliasesCanonical,
            'organo_score_nih_by_nombre' => $this->organoScoreNihByNombre,
            'has_active_symptoms' => $this->hasActiveSymptoms(),
        ];
    }
}
