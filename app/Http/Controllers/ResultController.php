<?php

namespace App\Http\Controllers;

use App\Http\Requests\Results\StoreResult;
use App\Http\Requests\Results\UpdateResult;
use App\Services\ResultServices;

class ResultController extends Controller
{
    public function __construct(
        protected ResultServices $resultServices
    ) {
    }

    // Obtiene todos los resultados
    public function index()
    {
        return $this->resultServices->getAllResults();
    }

    // Obtiene un resultado por su id
    public function show($id)
    {
        return $this->resultServices->getResultById($id);
    }

    // Crea un resultado
    public function store(StoreResult $storeRequest)
    {
        return $this->resultServices->createResult($storeRequest->all());
    }

    // Actualiza un resultado
    public function update(UpdateResult $updateRequest, $id)
    {
        return $this->resultServices->updateResult($id, $updateRequest->all());
    }

    // Elimina un resultado
    public function destroy($id)
    {
        return $this->resultServices->deleteResult($id);
    }
}
