<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WeightedIntervalScheduler;

class WeightedIntervalSchedulerTest extends TestCase
{
    public function testComputesOptimalSchedule()
    {
        $scheduler = new WeightedIntervalScheduler();

        // Times are arbitrary integers representing timestamps
        $tasks = [
            ['name' => 'A', 'start' => 1, 'end' => 3, 'value' => 5],
            ['name' => 'B', 'start' => 2, 'end' => 5, 'value' => 6],
            ['name' => 'C', 'start' => 4, 'end' => 6, 'value' => 5],
            ['name' => 'D', 'start' => 6, 'end' => 7, 'value' => 4],
            ['name' => 'E', 'start' => 5, 'end' => 8, 'value' => 11],
            ['name' => 'F', 'start' => 7, 'end' => 9, 'value' => 2],
        ];

        $result = $scheduler->computeOptimalSchedule($tasks);

        // Optimal set should be A + C + D with total 14, or B + D (10) vs E (11). Best is A+C+D.
        $this->assertEquals(14.0, $result['total_value']);

        $selectedNames = array_map(fn ($t) => $t['name'], $result['selected']);
        $this->assertSame(['A', 'C', 'D'], $selectedNames);
    }
}