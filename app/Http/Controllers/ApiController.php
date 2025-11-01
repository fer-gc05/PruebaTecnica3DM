<?php

namespace App\Http\Controllers;

use App\Services\ImprovementService;
use App\Services\ReportService;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function __construct(
        protected ImprovementService $improvementService,
        protected ReportService $reportService
    ) {
    }

    // Realiza la carga inicial de datos
    public function performInitLoad()
    {
        return response()->json($this->improvementService->performInitLoad(), Response::HTTP_OK);
    }

    // Realiza el barrido de datos
    public function performSweep()
    {
        return response()->json($this->improvementService->performSweep(), Response::HTTP_OK);
    }

    // Genera el reporte final con todas las métricas
    public function generateReport()
    {
        return response()->json($this->reportService->generateFinalReport(), Response::HTTP_OK);
    }
}
