<?php

namespace App\Jobs;

use App\Models\InformeClinico;
use App\Services\Documental\EvidenciaClientService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class GenerateClinicalReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries = 1;

    public function __construct(
        public int $informeClinicoId,
        public array $payload
    ) {
    }

    public function handle(EvidenciaClientService $client): void
    {
        $informe = InformeClinico::findOrFail($this->informeClinicoId);

        if ($informe->status === 'cancelled') {
            Log::info('GenerateClinicalReportJob CANCELLED before start', [
                'informe_clinico_id' => $this->informeClinicoId,
            ]);

            return;
        }

        $informe->update([
            'status' => 'processing',
            'started_at' => now(),
            'error_message' => null,
        ]);

        Log::info('GenerateClinicalReportJob START', [
            'informe_clinico_id' => $this->informeClinicoId,
            'payload' => $this->payload,
        ]);

        try {
            $response = $client->generateClinicalReport($this->payload);

            $informe->refresh();

            if ($informe->status === 'cancelled') {
                Log::info('GenerateClinicalReportJob CANCELLED after Flask response', [
                    'informe_clinico_id' => $this->informeClinicoId,
                ]);

                return;
            }

            $status = $response['status'] ?? null;

            Log::info('GenerateClinicalReportJob RESPONSE', [
                'informe_clinico_id' => $this->informeClinicoId,
                'status' => $response['status'] ?? null,
                'llm_used' => $response['llm_used'] ?? null,
                'error_type' => $response['error_type'] ?? null,
                'fallback_reason' => $response['fallback_reason'] ?? null,
            ]);

            $traceability = [
                'llm_used' => $response['llm_used'] ?? false,
                'llm_model' => $response['llm_model'] ?? null,
                'error_type' => $response['error_type'] ?? null,
                'fallback_reason' => $response['fallback_reason'] ?? null,
                'technical_detail' => $response['technical_detail'] ?? null,
                'warnings' => $response['warnings'] ?? [],
                'sources_count' => count($response['sources'] ?? []),
            ];

            $llmUsed = (bool) ($response['llm_used'] ?? false);

            $informe->update([
                'status' => $status === 'completed' ? 'completed' : 'fallback',
                'clinical_report' => $response['clinical_report'] ?? null,
                'traceability' => $traceability,
                'llm_used' => $llmUsed,
                'llm_model' => $response['llm_model'] ?? null,
                'fallback_reason' => $response['fallback_reason'] ?? null,
                'error_message' => $status === 'fallback'
                    ? ($response['fallback_reason'] ?? 'Informe generado en modo fallback.')
                    : null,
                'generated_at' => $response['generated_at'] ?? now(),
                'finished_at' => now(),
            ]);
        } catch (Throwable $e) {
            $informe->refresh();

            if ($informe->status === 'cancelled') {
                Log::info('GenerateClinicalReportJob CANCELLED after exception', [
                    'informe_clinico_id' => $this->informeClinicoId,
                    'error' => $e->getMessage(),
                ]);

                return;
            }

            $informe->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'finished_at' => now(),
            ]);

            Log::error('GenerateClinicalReportJob ERROR', [
                'informe_clinico_id' => $this->informeClinicoId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(Throwable $exception): void
    {
        InformeClinico::whereKey($this->informeClinicoId)->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
            'fallback_reason' => $exception->getMessage(),
            'finished_at' => now(),
        ]);

        Log::critical('GenerateClinicalReportJob FAILED', [
            'informe_clinico_id' => $this->informeClinicoId,
            'error' => $exception->getMessage(),
        ]);
    }
}