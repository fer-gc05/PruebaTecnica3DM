<?php

namespace App\Http\Controllers;

use App\Services\ImprovementService;
use App\Services\ReportService;

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
        return $this->improvementService->performInitLoad();
    }

    // Realiza el barrido de datos
    public function performSweep()
    {
        return $this->improvementService->performSweep();
    }

    // Genera el reporte final con todas las métricas
    public function generateReport()
    {
        return response()->json($this->reportService->generateFinalReport(), 200);
    }
}
