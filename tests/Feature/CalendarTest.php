<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_calendar_returns_services_and_slots()
    {
        $response = $this->getJson('/api/calendar');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'services' => [
                [
                    'id',
                    'name',
                    'slot_duration_minutes',
                    'slot_interval_minutes',
                    'cleanup_break_minutes',
                    'max_clients_per_slot',
                    'bookable_days_ahead',
                    'working_hours',
                    'breaks',
                    'off_dates',
                    'time_slots',
                ]
            ]
        ]);
    }
}
