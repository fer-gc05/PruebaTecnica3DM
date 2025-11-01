<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ReportService
{
    public function __construct(protected ResultServices $resultServices)
    {
    }

    public function generateFinalReport(): array
    {
        $detailedMetrics = $this->resultServices->getDetailedMetrics();
        $categoryDistribution = $this->resultServices->getCategoryDistribution();
        $avgAttemptsConversion = $this->resultServices->getAverageAttemptsForBadConversion();
        $basicMetrics = $this->resultServices->getMetrics();

        $initialLoadCalls = $this->getInitialLoadCalls();

        $improvementCalls = $this->getImprovementCalls();

        $totalCalls = $initialLoadCalls + $improvementCalls;

        $sweepsStats = $this->getSweepsStatistics();

        return [
            'summary' => [
                'initial_load_calls' => $initialLoadCalls,
                'improvement_calls' => $improvementCalls,
                'total_calls' => $totalCalls,
                'total_sweeps_estimated' => $sweepsStats['estimated_sweeps'],
                'results_improved' => $sweepsStats['results_improved'],
            ],
            'final_distribution' => [
                'total_results' => $detailedMetrics['total_results'],
                'bads' => $detailedMetrics['bads'],
                'mediums' => $detailedMetrics['mediums'],
                'goods' => $detailedMetrics['goods'],
                'bad_percentage' => $categoryDistribution['bad_percentage'],
                'medium_percentage' => $categoryDistribution['medium_percentage'],
                'good_percentage' => $categoryDistribution['good_percentage'],
            ],
            'attempts_statistics' => [
                'average_attempts' => $detailedMetrics['avg_attempts'],
                'max_attempts' => $detailedMetrics['max_attempts'],
                'min_attempts' => $detailedMetrics['min_attempts'],
                'total_attempts' => $detailedMetrics['total_attempts'],
                'average_attempts_for_bad_conversion' => $avgAttemptsConversion,
            ],
        ];
    }

    private function getInitialLoadCalls(): int
    {
        return DB::table('results')
            ->where('attempts', 1)
            ->count();
    }

    private function getImprovementCalls(): int
    {
        return DB::table('results')
            ->where('attempts', '>', 1)
            ->selectRaw('SUM(attempts - 1) as total_improvement_calls')
            ->value('total_improvement_calls') ?? 0;
    }

    private function getSweepsStatistics(): array
    {
        $resultsImproved = DB::table('results')
            ->where('attempts', '>', 1)
            ->count();

        $estimatedSweeps = $resultsImproved > 0 ? max(1, (int) ceil($resultsImproved / 10)) : 0;

        return [
            'estimated_sweeps' => $estimatedSweeps,
            'results_improved' => $resultsImproved,
        ];
    }
}
