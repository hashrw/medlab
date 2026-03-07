<?php

namespace App\Jobs;

use App\Models\Diagnostico;
use App\Models\SetEvidencia;
use App\Services\Documental\EvidenciaClientService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateEvidencePackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $diagnosticoId,
        public readonly array $inferenciaPayload // el JSON que ya generas (o parte)
    ) {
    }

    public function handle(EvidenciaClientService $client): void
    {
        $pack = SetEvidencia::firstOrCreate(
            ['diagnostico_id' => $this->diagnosticoId],
            ['status' => 'queued']
        );

        $pack->update(['status' => 'processing', 'error_message' => null]);

        try {
            // Validación mínima
            Diagnostico::findOrFail($this->diagnosticoId);

            $payload = $client->fetchEvidence($this->inferenciaPayload);

            $pack->update([
                'status' => 'done',
                'payload' => $payload,
            ]);
        } catch (Throwable $e) {
            $pack->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e; // para que Laravel marque el job como failed y puedas verlo
        }
    }
}
