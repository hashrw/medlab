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
    ) {}

    public function hasActiveSymptoms(): bool
    {
        return !empty($this->activeSintomaIds) || !empty($this->activeAliasesCanonical);
    }

    public function scoreForOrganoNombre(string $organoNombre): ?int
    {
        return $this->organoScoreNihByNombre[$organoNombre] ?? null;
    }
}
