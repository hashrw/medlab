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
            Log::info('GenerateClinicalReportJob RESPONSE', [
                'informe_clinico_id' => $this->informeClinicoId,
                'status' => $response['status'] ?? null,
                'llm_used' => $response['traceability']['llm_used'] ?? null,
                'fallback_reason' => $response['traceability']['fallback_reason'] ?? null,
            ]);

            $traceability = $response['traceability'] ?? [];
            $llmUsed = (bool) ($traceability['llm_used'] ?? false);

            $informe->update([
                'status' => $llmUsed ? 'completed' : 'fallback',
                'clinical_report' => $response['clinical_report'] ?? null,
                'traceability' => $traceability,
                'llm_used' => $llmUsed,
                'llm_model' => $traceability['llm_model'] ?? null,
                'fallback_reason' => $traceability['fallback_reason'] ?? null,
                'generated_at' => $response['generated_at'] ?? now(),
                'finished_at' => now(),
            ]);
        } catch (Throwable $e) {
            $informe->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                #'fallback_reason' => $e->getMessage(),
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