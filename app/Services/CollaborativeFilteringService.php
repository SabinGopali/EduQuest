<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Storage;

class CollaborativeFilteringService
{
    /**
     * Build item-based collaborative filtering recommendations for a given student
     * and persist them to storage for quick retrieval.
     */
    public function updateRecommendationsForStudent(int $studentId): void
    {
        $bookings = Booking::query()
            ->select(['student_id', 'coursedetail_id', 'status'])
            ->whereIn('status', ['booked', 'approved'])
            ->get();

        if ($bookings->isEmpty()) {
            Storage::put($this->pathFor($studentId), json_encode([ 'items' => [], 'generatedAt' => now()->toISOString() ]));
            return;
        }

        $studentToItems = [];
        $itemToStudents = [];
        foreach ($bookings as $b) {
            $sid = (int)$b->student_id;
            $iid = (int)$b->coursedetail_id;
            if (!isset($studentToItems[$sid])) { $studentToItems[$sid] = []; }
            if (!isset($itemToStudents[$iid])) { $itemToStudents[$iid] = []; }
            $studentToItems[$sid][$iid] = 1;
            $itemToStudents[$iid][$sid] = 1;
        }

        $itemVectorsNorm = [];
        foreach ($itemToStudents as $itemId => $students) {
            $itemVectorsNorm[$itemId] = sqrt(count($students));
        }

        $similarity = [];
        $itemIds = array_keys($itemToStudents);
        $numItems = count($itemIds);
        for ($i = 0; $i < $numItems; $i++) {
            $a = $itemIds[$i];
            for ($j = $i + 1; $j < $numItems; $j++) {
                $b = $itemIds[$j];
                $common = 0;
                foreach ($itemToStudents[$a] as $sid => $_) {
                    if (isset($itemToStudents[$b][$sid])) { $common++; }
                }
                if ($common === 0) { continue; }
                $den = ($itemVectorsNorm[$a] * $itemVectorsNorm[$b]);
                if ($den <= 0) { continue; }
                $sim = $common / $den;
                if ($sim <= 0) { continue; }
                if (!isset($similarity[$a])) { $similarity[$a] = []; }
                if (!isset($similarity[$b])) { $similarity[$b] = []; }
                $similarity[$a][$b] = $sim;
                $similarity[$b][$a] = $sim;
            }
        }

        $owned = $studentToItems[$studentId] ?? [];
        $scores = [];
        foreach ($owned as $ownedItemId => $_) {
            if (!isset($similarity[$ownedItemId])) { continue; }
            foreach ($similarity[$ownedItemId] as $otherItemId => $sim) {
                if (isset($owned[$otherItemId])) { continue; }
                if (!isset($scores[$otherItemId])) { $scores[$otherItemId] = 0.0; }
                $scores[$otherItemId] += $sim;
            }
        }

        arsort($scores);
        $top = array_slice($scores, 0, 20, true);

        $payload = [
            'items' => array_map(function ($itemId, $score) {
                return [ 'coursedetail_id' => (int)$itemId, 'score' => round($score, 6) ];
            }, array_keys($top), array_values($top)),
            'generatedAt' => now()->toISOString(),
        ];

        Storage::put($this->pathFor($studentId), json_encode($payload));
    }

    public function getRecommendationsForStudent(int $studentId): array
    {
        $path = $this->pathFor($studentId);
        if (!Storage::exists($path)) { return []; }
        $json = Storage::get($path);
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['items'])) { return []; }
        return $data['items'];
    }

    protected function pathFor(int $studentId): string
    {
        return 'recommendations/student_' . $studentId . '.json';
    }
}


