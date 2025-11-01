<?php

namespace App\Repositories;

use App\Models\Result;
use App\Repositories\Contracts\ResultRepository as ResultRepositoryContract;
use Illuminate\Support\Facades\DB;

class ResultRepository implements ResultRepositoryContract
{
    public function __construct(protected Result $model)
    {
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $result = $this->model->find($id);
        if (!$result) {
            return false;
        }

        return $result->update($data);
    }

    public function delete($id)
    {
        $result = $this->model->find($id);
        if (!$result) {
            return false;
        }

        return $result->delete();
    }

    public function getBadResults()
    {
        return $this->model->where('category', 'bad')->get();
    }

    public function getMetrics()
    {
        return [
            'total_results' => $this->model->count(),
            'bads' => $this->model->where('category', 'bad')->count(),
            'mediums' => $this->model->where('category', 'medium')->count(),
            'goods' => $this->model->where('category', 'good')->count(),
        ];
    }

    public function bulkUpdate($updates)
    {
        if (empty($updates)) {
            return false;
        }

        return DB::transaction(function () use ($updates) {
            $updated = 0;
            foreach ($updates as $update) {
                $id = $update['id'];
                unset($update['id']);

                $updated += DB::table($this->model->getTable())
                    ->where('id', $id)
                    ->update($update);
            }

            return $updated;
        });
    }

    public function getDetailedMetrics(): array
    {
        $results = $this->model->selectRaw('
            COUNT(*) as total_results,
            SUM(CASE WHEN category = "bad" THEN 1 ELSE 0 END) as bads,
            SUM(CASE WHEN category = "medium" THEN 1 ELSE 0 END) as mediums,
            SUM(CASE WHEN category = "good" THEN 1 ELSE 0 END) as goods,
            AVG(attempts) as avg_attempts,
            MAX(attempts) as max_attempts,
            MIN(attempts) as min_attempts,
            SUM(attempts) as total_attempts
        ')->first();

        return [
            'total_results' => (int) $results->total_results,
            'bads' => (int) $results->bads,
            'mediums' => (int) $results->mediums,
            'goods' => (int) $results->goods,
            'avg_attempts' => round((float) $results->avg_attempts, 2),
            'max_attempts' => (int) $results->max_attempts,
            'min_attempts' => (int) $results->min_attempts,
            'total_attempts' => (int) $results->total_attempts,
        ];
    }

    public function getAverageAttemptsForBadConversion(): float|null
    {
        $result = $this->model
            ->where('category', '!=', 'bad')
            ->where('attempts', '>', 1)
            ->selectRaw('AVG(attempts) as avg_attempts')
            ->first();

        return $result && $result->avg_attempts ? round((float) $result->avg_attempts, 2) : null;
    }

    public function getCategoryDistribution(): array
    {
        $total = $this->model->count();

        if ($total === 0) {
            return [
                'bad_percentage' => 0,
                'medium_percentage' => 0,
                'good_percentage' => 0,
            ];
        }

        $bads = $this->model->where('category', 'bad')->count();
        $mediums = $this->model->where('category', 'medium')->count();
        $goods = $this->model->where('category', 'good')->count();

        return [
            'bad_percentage' => round(($bads / $total) * 100, 2),
            'medium_percentage' => round(($mediums / $total) * 100, 2),
            'good_percentage' => round(($goods / $total) * 100, 2),
        ];
    }
}
