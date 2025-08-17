<?php

namespace Tests\Feature;

use Tests\TestCase;

class StudyScheduleFeatureTest extends TestCase
{
    public function testComputeRouteDisplaysResult()
    {
        $payload = [
            'tasks' => [
                ['name' => 'Task 1', 'start' => 1, 'end' => 3, 'value' => 5],
                ['name' => 'Task 2', 'start' => 2, 'end' => 5, 'value' => 6],
                ['name' => 'Task 3', 'start' => 4, 'end' => 6, 'value' => 5],
            ],
        ];

        $response = $this->post('/optimal-schedule/compute', $payload);

        $response->assertStatus(200);
        $response->assertSee('Optimal Study Scheduler');
        $response->assertSee('Optimal Total Value');
    }
}