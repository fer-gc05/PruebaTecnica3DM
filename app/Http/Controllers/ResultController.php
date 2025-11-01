<?php

namespace App\Http\Controllers;

use App\Http\Requests\Results\StoreResult;
use App\Http\Requests\Results\UpdateResult;
use App\Services\ResultServices;
use Symfony\Component\HttpFoundation\Response;

class ResultController extends Controller
{
    public function __construct(
        protected ResultServices $resultServices
    ) {
    }

    // Obtiene todos los resultados
    public function index()
    {
        return response()->json($this->resultServices->getAllResults(), Response::HTTP_OK);
    }

    // Obtiene un resultado por su id
    public function show($id)
    {
        return response()->json($this->resultServices->getResultById($id), Response::HTTP_OK);
    }

    // Crea un resultado
    public function store(StoreResult $storeRequest)
    {
        return response()->json($this->resultServices->createResult($storeRequest->all()), Response::HTTP_CREATED);
    }

    // Actualiza un resultado
    public function update(UpdateResult $updateRequest, $id)
    {
        return response()->json($this->resultServices->updateResult($id, $updateRequest->all()), Response::HTTP_OK);
    }

    // Elimina un resultado
    public function destroy($id)
    {
        return response()->json($this->resultServices->deleteResult($id), Response::HTTP_NO_CONTENT);
    }
}
