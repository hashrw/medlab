<?php

namespace App\Domain\Clinical;

final class RespuestaInferencia
{
    public function __construct(
        public readonly CasoClinico $casoClinico,
        public readonly ResultadoInferencia $resultadoInferencia
    ) {}

    public function toArray(): array
    {
        return [
            'caso_clinico' => $this->casoClinico->toArray(),
            'resultado_inferencia' => $this->resultadoInferencia->toArray(),
        ];
    }
}
