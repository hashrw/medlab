<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Services\InferenciaDiagnosticoService;
use App\Domain\Clinical\CasoClinicoFactory;
use App\Domain\Clinical\ResultadoInferencia;
use App\Domain\Clinical\RespuestaInferencia;

class InferenciaController extends Controller
{
    public function __construct(
        private readonly InferenciaDiagnosticoService $inferenciaDiagnosticoService,
    ) {
    }

    /**
     * POST /api/clinical/inferencia
     * Body: { "paciente_id": 123, "as_of": "2026-02-25T10:00:00+01:00" (opcional) }
     */
    public function ejecutar(Request $request)
    {
        $data = $request->validate([
            'paciente_id' => ['required', 'integer', 'exists:pacientes,id'],
            'dispatch_evidence' => ['nullable', 'boolean'],
        ]);

        $paciente = Paciente::findOrFail($data['paciente_id']);
        $asOf = isset($data['as_of']) ? Carbon::parse($data['as_of']) : null;
        $dispatchEvidence = $data['dispatch_evidence'] ?? true;

        // 1) Entrada estructurada (contrato)
        $casoClinico = CasoClinicoFactory::fromPaciente($paciente, $asOf);

        // 2) DSS determinista
        [$diag, $fallback] = $this->inferenciaDiagnosticoService->ejecutar($paciente);
        $resultado = ResultadoInferencia::fromServiceResult($diag, $fallback, $casoClinico);
        $respuesta = (new RespuestaInferencia($casoClinico, $resultado))->toArray();

        // 3) Salida estructurada (contrato) dispara solo si match + diagnostico_id
        if ($dispatchEvidence && $resultado->status === 'match' && $resultado->diagnostico_id) {
            
            \App\Jobs\GenerateEvidencePackJob::dispatch(
                diagnosticoId: $resultado->diagnostico_id,
                inferenciaPayload: $respuesta
            );
        }

        return response($respuesta);
    }
}
