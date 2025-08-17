<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeightedIntervalScheduler;

class StudyScheduleController extends Controller
{
    private WeightedIntervalScheduler $scheduler;

    public function __construct(WeightedIntervalScheduler $scheduler)
    {
        $this->scheduler = $scheduler;
    }

    public function index()
    {
        return view('home.scheduler');
    }

    public function compute(Request $request)
    {
        $rawTasks = $request->input('tasks', []);
        $normalized = [];

        foreach ($rawTasks as $raw) {
            $name = isset($raw['name']) ? (string) $raw['name'] : '';
            $value = isset($raw['value']) ? (float) $raw['value'] : 0.0;

            // Accept time or datetime-local inputs; fallback to integers
            $start = $this->parseTimeToEpoch($raw['start'] ?? null);
            $end = $this->parseTimeToEpoch($raw['end'] ?? null);

            $normalized[] = [
                'name' => $name,
                'start' => $start,
                'end' => $end,
                'value' => $value,
            ];
        }

        $result = $this->scheduler->computeOptimalSchedule($normalized);

        return view('home.scheduler', [
            'result' => $result,
        ]);
    }

    private function parseTimeToEpoch($value): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        $ts = strtotime((string) $value);
        if ($ts === false) {
            return 0;
        }
        return (int) $ts;
    }
}