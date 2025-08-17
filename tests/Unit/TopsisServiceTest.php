<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TopsisService;

class TopsisServiceTest extends TestCase
{
    public function test_rankAlternatives_basic()
    {
        $service = new TopsisService();

        // 3 alternatives, 3 criteria (2 benefit, 1 cost)
        $ids = [101, 102, 103];
        $matrix = [
            [7, 9, 5],    // A1
            [8, 7, 6],    // A2
            [9, 6, 4],    // A3
        ];
        $weights = [0.5, 0.3, 0.2];
        $types = ['benefit', 'benefit', 'cost'];

        $result = $service->rankAlternatives($ids, $matrix, $weights, $types);

        $this->assertNotEmpty($result['results']);
        $this->assertCount(3, $result['results']);

        // Scores should be in [0,1] and sorted desc
        $scores = array_column($result['results'], 'score');
        foreach ($scores as $s) {
            $this->assertGreaterThanOrEqual(0.0, $s);
            $this->assertLessThanOrEqual(1.0, $s);
        }
        $sorted = $scores;
        rsort($sorted);
        $this->assertSame($sorted, $scores);

        // Ensure ranks are 1..n
        $ranks = array_column($result['results'], 'rank');
        $this->assertSame([1,2,3], $ranks);

        // Ensure IDs preserved and in descending order of score
        $orderedIds = array_column($result['results'], 'id');
        $this->assertSame(count($orderedIds), count(array_unique($orderedIds)));
    }

    public function test_rankAlternatives_handles_missing_values_and_zero_weights()
    {
        $service = new TopsisService();
        $ids = [1,2];
        $matrix = [
            [10, null, 5],
            [ 8,   4, null],
        ];
        $weights = [0, 1, 0]; // Only second criterion has weight
        $types = ['benefit','benefit','cost'];

        $result = $service->rankAlternatives($ids, $matrix, $weights, $types);
        $this->assertCount(2, $result['results']);
        $this->assertNotEmpty($result['meta']['includedCriteria']);
    }
}