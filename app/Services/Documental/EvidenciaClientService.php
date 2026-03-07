<?php

namespace App\Services\Documental;

use Illuminate\Support\Facades\Http;

class EvidenciaClientService
{
    public function fetchEvidence(array $requestPayload): array
    {
        if (config('evidence.stub', true)) {
            return [
                'status' => 'ok',
                'generated_at' => now()->toISOString(),
                'citations' => [],
                'evidence_map' => [],
                'warnings' => [],
                'meta' => [
                    'stub' => true,
                    'message' => 'EvidenceClient en modo stub. Flask no consultado.',
                ],
            ];
        }

        $url = rtrim((string) config('evidence.base_url'), '/') . '/evidence';
        $timeout = (int) config('evidence.timeout', 20);

        $resp = Http::timeout($timeout)
            ->acceptJson()
            ->asJson()
            ->post($url, $requestPayload);

        // Si Flask devuelve 4xx/5xx, se lanza lanza excepción con detalle
        $resp->throw();

        $json = $resp->json();
        return is_array($json) ? $json : ['status' => 'error', 'error' => 'Respuesta no JSON'];
    }
}
