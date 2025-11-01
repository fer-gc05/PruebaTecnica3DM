<?php

namespace App\Services;

use App\Repositories\ResultRepository;

class ResultServices
{
    public function __construct(protected ResultRepository $resultRepository)
    {
    }

    // Obtiene todos los resultados
    public function getAllResults()
    {
        return $this->resultRepository->all();
    }

    // Obtiene un resultado por su id
    public function getResultById($id)
    {
        return $this->resultRepository->find($id);
    }

    // Crea un resultado
    public function createResult($data)
    {
        return $this->resultRepository->create($data);
    }

    // Actualiza un resultado
    public function updateResult($id, $data)
    {
        return $this->resultRepository->update($id, $data);
    }

    // Actualiza varios resultados
    public function bulkUpdateResults($updates)
    {
        return $this->resultRepository->bulkUpdate($updates);
    }

    // Elimina un resultado
    public function deleteResult($id)
    {
        return $this->resultRepository->delete($id);
    }

    // Obtiene los resultados malos
    public function getBadResults()
    {
        return $this->resultRepository->getBadResults();
    }

    // Obtiene las métricas
    public function getMetrics()
    {
        return $this->resultRepository->getMetrics();
    }

    // Obtiene métricas detalladas para reportes
    public function getDetailedMetrics()
    {
        return $this->resultRepository->getDetailedMetrics();
    }

    // Obtiene el promedio de intentos para convertir bad
    public function getAverageAttemptsForBadConversion()
    {
        return $this->resultRepository->getAverageAttemptsForBadConversion();
    }

    // Obtiene la distribución de categorías con porcentajes
    public function getCategoryDistribution(): array
    {
        return $this->resultRepository->getCategoryDistribution();
    }
}
