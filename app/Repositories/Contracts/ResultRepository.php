<?php

namespace App\Repositories\Contracts;

interface ResultRepository
{
    // Acciones basicas crud, read(all), create, update, delete y busqueda por id(find)
    public function all();

    public function find($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    // Acciones especificas relacionadas con los resultados.
    public function getBadResults(); // Planteada para lo obtencion de resultados con categoria bad.

    public function getMetrics(); // Planteada para lo obtencion de metricas de los resultados.

    public function bulkUpdate($updates); // Actualización masiva de resultados.

    public function getDetailedMetrics(); // Métricas detalladas para reportes.

    public function getAverageAttemptsForBadConversion(); // Promedio de intentos para convertir bad a medium/good.

    public function getCategoryDistribution(); // Distribución de categorías con porcentajes.
}
