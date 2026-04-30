<?php

namespace App\Services\Documental;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class EvidenciaClientService
{
    public function generateClinicalReport(array $requestPayload): array
    {
        if (config('evidence.stub', true)) {
            return [
                'api_version' => 'v1',
                'status' => 'ok',
                'generated_at' => now()->toISOString(),
                'clinical_report' => [
                    'titulo' => 'Informe clínico generado en modo stub',
                    'resumen_clinico' => 'Clinical Report en modo stub. Flask no consultado.',
                    'sospecha_diagnostica' => null,
                    'organos_afectados' => [],
                    'hallazgos_relevantes' => [],
                    'interpretacion_clinica' => null,
                    'recomendaciones_validacion_medica' => [],
                ],
                'traceability' => [
                    'sources' => [],
                    'warnings' => [],
                    'model' => 'stub',
                ],
                'meta' => [
                    'stub' => true,
                    'message' => 'ClinicalReportClient en modo stub. Flask no consultado.',
                ],
            ];
        }

        Log::info('clinical report payload', $requestPayload);

        $url = rtrim((string) config('evidence.base_url'), '/') . '/clinical-report';
        $timeout = (int) config('evidence.timeout', 20);

        $resp = Http::timeout($timeout)
            ->acceptJson()
            ->asJson()
            ->post($url, $requestPayload);

        $resp->throw();

        $json = $resp->json();

        if (!is_array($json)) {
            throw new RuntimeException('Clinical report service returned a non-JSON-array response.');
        }

        $requiredKeys = [
            'api_version',
            'status',
            'generated_at',
            'clinical_report',
            'traceability',
        ];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $json)) {
                throw new RuntimeException("Clinical report service response missing required key: {$key}");
            }
        }

        if ($json['api_version'] !== 'v1') {
            throw new RuntimeException("Unsupported clinical report API version: {$json['api_version']}");
        }

        if ($json['status'] !== 'ok') {
            throw new RuntimeException('Clinical report service returned status != ok');
        }

        if (!is_array($json['clinical_report']) || !is_array($json['traceability'])) {
            throw new RuntimeException('Clinical report service returned invalid structured fields.');
        }

        return $json;
    }

    public function fetchEvidence(array $requestPayload): array
    {
        return $this->generateClinicalReport($requestPayload);
    }
}