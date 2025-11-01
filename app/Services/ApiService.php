<?php

namespace App\Services;

use App\Repositories\Contracts\ApiClientRepository;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class ApiService implements ApiClientRepository
{
    public function __construct(
        protected int $maxRetries = 1,
        protected float $sleepTime = 0.5
    ) {
    }

    // Obtiene los resultados desde la API de forma masiva
    public function getResultsMassive(?int $count = null, int $concurrency = 20)
    {
        $baseUrl = config('services.testapi.base_url');
        $userId = config('services.testapi.user_id');
        $url = $baseUrl . '?user_id=' . $userId;

        $totalCount = $count ?? 1;
        $results = [];
        $remaining = $totalCount;

        while ($remaining > 0) {
            $batchSize = min($concurrency, $remaining);

            $responses = Http::pool(function (Pool $pool) use ($url, $batchSize) {
                $requests = [];
                for ($i = 0; $i < $batchSize; $i++) {
                    $requests[] = $pool->timeout(5)->get($url);
                }

                return $requests;
            }, concurrency: $batchSize);

            foreach ($responses as $response) {
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['category'])) {
                        $results[] = [
                            'user_id' => $data['user_id'] ?? null,
                            'value' => $data['value'] ?? null,
                            'category' => $data['category'] ?? null,
                            'timestamp' => $data['timestamp'] ?? null,
                            'ip_address' => $data['ip'] ?? $data['ip_address'] ?? null,
                        ];
                    }
                }
            }

            $remaining -= $batchSize;

            if ($remaining > 0) {
                usleep(100000);
            }
        }
        return $results;
    }

    // Obtiene un resultado individual
    public function getResultIndividual()
    {
        $baseUrl = config('services.testapi.base_url');
        $userId = config('services.testapi.user_id');
        $url = $baseUrl . '?user_id=' . $userId;

        $response = Http::timeout(5)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['category'])) {
                return [
                    'user_id' => $data['user_id'] ?? null,
                    'value' => $data['value'] ?? null,
                    'category' => $data['category'] ?? null,
                    'timestamp' => $data['timestamp'] ?? null,
                    'ip_address' => $data['ip'] ?? $data['ip_address'] ?? null,
                ];
            }
        }

        return null;
    }
}
