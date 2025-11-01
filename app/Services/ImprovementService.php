<?php

namespace App\Services;

class ImprovementService
{
    // Total de llamadas a la API
    private int $total_calls = 0;

    public function __construct(protected ResultServices $resultServices, protected ApiService $apiService)
    {
    }

    // Realiza la carga inicial de datos
    public function performInitLoad($count = 100)
    {
        $this->total_calls = 0;
        $attempts = 1;

        $results = $this->apiService->getResultsMassive($count, 20) ?? [];

        foreach ($results as $result) {
            if ($result) {
                $this->resultServices->createResult($result + ['attempts' => $attempts]);
                $this->total_calls++;
                $attempts++;
            }
        }

        return [
            'total_calls' => $this->total_calls,
            'metrics' => $this->getMetrics(),
        ];
    }

    // Realiza el barrido de datos
    public function performSweep()
    {
        $sweeps_count = 0;
        $sweep_calls = 0;

        while ($this->hasBadResults()) {
            $sweeps_count++;
            $badResults = $this->resultServices->getBadResults();
            $badCount = count($badResults);

            if ($badCount === 0) {
                break;
            }

            $updates = [];

            foreach ($badResults as $badResult) {
                $newResult = $this->apiService->getResultIndividual();
                $sweep_calls++;

                if ($newResult && in_array($newResult['category'], ['medium', 'good'])) {
                    $updates[] = [
                        'id' => $badResult['id'],
                        'value' => $newResult['value'],
                        'category' => $newResult['category'],
                        'timestamp' => $newResult['timestamp'] ?? $badResult['timestamp'],
                        'ip_address' => $newResult['ip_address'] ?? $badResult['ip_address'],
                        'attempts' => ($badResult['attempts'] ?? 1) + 1,
                        'updated_at' => now()->toDateTimeString(),
                    ];
                }

                usleep(100000);
            }

            $improvedCount = count($updates);

            if ($improvedCount > 0) {
                $this->resultServices->bulkUpdateResults($updates);
            }
        }

        return [
            'sweeps' => $sweeps_count,
            'sweep_calls' => $sweep_calls,
            'remaining_bads' => $this->hasBadResults() ? count($this->resultServices->getBadResults()) : 0,
            'all_bads_eliminated' => !$this->hasBadResults(),
            'metrics' => $this->getMetrics(),
        ];
    }

    // Verifica si hay resultados malos
    private function hasBadResults()
    {
        return count($this->resultServices->getBadResults()) > 0;
    }

    // Obtiene las métricas
    private function getMetrics()
    {
        return $this->resultServices->getMetrics();
    }
}
