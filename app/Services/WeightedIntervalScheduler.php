<?php

namespace App\Services;

class WeightedIntervalScheduler
{
    /**
     * Compute optimal non-overlapping schedule maximizing total value.
     *
     * @param array<int, array{ id?: int|string, name: string, start: int, end: int, value: float|int }>$tasks
     * @return array{ total_value: float, selected: array<int, array>, sorted: array<int, array> }
     */
    public function computeOptimalSchedule(array $tasks): array
    {
        $filteredTasks = [];
        foreach ($tasks as $task) {
            $start = isset($task['start']) ? (int) $task['start'] : 0;
            $end = isset($task['end']) ? (int) $task['end'] : 0;
            $value = isset($task['value']) ? (float) $task['value'] : 0.0;
            $name = isset($task['name']) ? (string) $task['name'] : '';
            $id = $task['id'] ?? null;

            if ($end > $start && $value > 0 && $name !== '') {
                $filteredTasks[] = [
                    'id' => $id,
                    'name' => $name,
                    'start' => $start,
                    'end' => $end,
                    'value' => $value,
                ];
            }
        }

        // Sort by end time
        usort($filteredTasks, function ($a, $b) {
            if ($a['end'] === $b['end']) {
                return $a['start'] <=> $b['start'];
            }
            return $a['end'] <=> $b['end'];
        });

        $n = count($filteredTasks);
        if ($n === 0) {
            return [
                'total_value' => 0.0,
                'selected' => [],
                'sorted' => [],
            ];
        }

        // Precompute array of finish times and predecessor indexes p[i]
        $finishTimes = array_map(fn ($t) => $t['end'], $filteredTasks);
        $p = array_fill(0, $n, -1);
        for ($i = 0; $i < $n; $i++) {
            $p[$i] = $this->findLastNonConflictingIndex($finishTimes, $filteredTasks[$i]['start']);
        }

        // DP table: dp[i] = optimal value considering tasks[0..i]
        $dp = array_fill(0, $n, 0.0);
        $choice = array_fill(0, $n, false);

        for ($i = 0; $i < $n; $i++) {
            $includeValue = $filteredTasks[$i]['value'];
            if ($p[$i] !== -1) {
                $includeValue += $dp[$p[$i]];
            }
            $excludeValue = $i > 0 ? $dp[$i - 1] : 0.0;

            if ($includeValue > $excludeValue) {
                $dp[$i] = $includeValue;
                $choice[$i] = true;
            } else {
                $dp[$i] = $excludeValue;
                $choice[$i] = false;
            }
        }

        // Reconstruct solution
        $selected = [];
        for ($i = $n - 1; $i >= 0; ) {
            if ($choice[$i]) {
                $selected[] = $filteredTasks[$i];
                $i = $p[$i];
            } else {
                $i = $i - 1;
            }
        }
        $selected = array_reverse($selected);

        return [
            'total_value' => $dp[$n - 1],
            'selected' => $selected,
            'sorted' => $filteredTasks,
        ];
    }

    /**
     * Binary search finishTimes to find the largest index j where finishTimes[j] <= startTime.
     * Returns -1 if none found.
     *
     * @param array<int, int> $finishTimes
     */
    private function findLastNonConflictingIndex(array $finishTimes, int $startTime): int
    {
        $low = 0;
        $high = count($finishTimes) - 1;
        $result = -1;

        while ($low <= $high) {
            $mid = intdiv($low + $high, 2);
            if ($finishTimes[$mid] <= $startTime) {
                $result = $mid;
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }

        return $result;
    }
}