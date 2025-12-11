<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\TimeSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_cannot_book_during_lunch_break()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->whereTime('start_datetime', '>=', '12:00')
            ->whereTime('start_datetime', '<', '13:00')
            ->first();

        $this->assertNull($slot, 'Slots should not exist during lunch break');
    }

    public function test_cannot_book_before_opening()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->whereTime('start_datetime', '<', '08:00')
            ->first();

        $this->assertNull($slot);
    }

    public function test_cannot_book_on_day_off()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->get()
            ->first(function ($s) {
                return $s->start_datetime->isSunday();
            });

        $this->assertNull($slot, 'Slots should not exist on Sunday (day off)');
    }

    public function test_cannot_book_on_off_date()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->whereDate('start_datetime', now()->addDays(2)->toDateString())
            ->first();

        $this->assertNull($slot);
    }

    public function test_cannot_book_nonexistent_slot()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $response = $this->postJson('/api/bookings', [
            'service_id' => $service->id,
            'time_slot_id' => 999999,
            'clients' => [
                ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com']
            ]
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_overbook_slot()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->where('start_datetime', '>', now())
            ->first();

        $this->postJson('/api/bookings', [
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
            'clients' => [
                ['first_name' => 'A', 'last_name' => 'A', 'email' => 'a@example.com'],
                ['first_name' => 'B', 'last_name' => 'B', 'email' => 'b@example.com'],
                ['first_name' => 'C', 'last_name' => 'C', 'email' => 'c@example.com'],
            ]
        ])->assertStatus(201);

        // Пытаемся добавить ещё одного
        $response = $this->postJson('/api/bookings', [
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
            'clients' => [
                ['first_name' => 'D', 'last_name' => 'D', 'email' => 'd@example.com'],
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['slot' => ['Slot is fully booked.']]);
    }

    public function test_can_create_booking_for_valid_slot()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->where('start_datetime', '>', now())
            ->first();

        $payload = [
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
            'clients' => [
                [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com'
                ]
            ]
        ];

        $response = $this->postJson('/api/bookings', $payload);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'status',
            'booking' => [
                'id',
                'service_id',
                'time_slot_id',
                'clients' => [
                    [
                        'id',
                        'first_name',
                        'last_name',
                        'email'
                    ]
                ]
            ]
        ]);
    }

    public function test_cannot_book_exactly_at_lunch_start()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->whereTime('start_datetime', '=', '12:00')
            ->first();

        $this->assertNull($slot, 'Slot at 12:00 should not exist');
    }
    public function test_can_book_first_slot_after_lunch()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->whereTime('start_datetime', '>=', '13:00')
            ->orderBy('start_datetime')
            ->first();

        $this->assertNotNull($slot, 'There should be a slot after lunch break');
        $this->assertTrue($slot->start_datetime->format('H:i') >= '13:00');
    }

    public function test_first_slot_is_not_before_opening_time()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->orderBy('start_datetime')
            ->first();

        $this->assertNotNull($slot, 'There should be at least one slot');
        $this->assertTrue($slot->start_datetime->format('H:i') >= '08:00');
    }

    public function test_cannot_book_exactly_at_closing_time()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->whereTime('start_datetime', '=', '20:00')
            ->first();

        $this->assertNull($slot, 'Slot at 20:00 should not exist');
    }


    public function test_can_book_on_last_bookable_day()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $date = now()->addDays($service->bookable_days_ahead - 1)->toDateString();

        $slot = TimeSlot::where('service_id', $service->id)
            ->whereDate('start_datetime', $date)
            ->first();

        $this->assertNotNull($slot, 'Slot should exist on last bookable day');
    }

    public function test_cannot_book_after_last_bookable_day()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $date = now()->addDays($service->bookable_days_ahead)->toDateString();

        $slot = TimeSlot::where('service_id', $service->id)
            ->whereDate('start_datetime', $date)
            ->first();

        $this->assertNull($slot, 'Slot should not exist after last bookable day');
    }

    public function test_can_book_exact_max_clients()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->where('start_datetime', '>', now())
            ->first();

        $clients = [];
        for ($i = 1; $i <= $service->max_clients_per_slot; $i++) {
            $clients[] = [
                'first_name' => "User$i",
                'last_name' => "Test",
                'email' => "user$i@example.com",
            ];
        }

        $response = $this->postJson('/api/bookings', [
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
            'clients' => $clients,
        ]);

        $response->assertStatus(201);
    }

    public function test_cannot_book_more_than_max_clients()
    {
        $service = Service::where('name', 'Men Haircut')->first();

        $slot = TimeSlot::where('service_id', $service->id)
            ->where('start_datetime', '>', now())
            ->first();

        $clients = [];
        for ($i = 1; $i <= $service->max_clients_per_slot + 1; $i++) {
            $clients[] = [
                'first_name' => "User$i",
                'last_name' => "Test",
                'email' => "user$i@example.com",
            ];
        }

        $response = $this->postJson('/api/bookings', [
            'service_id' => $service->id,
            'time_slot_id' => $slot->id,
            'clients' => $clients,
        ]);

        $response->assertStatus(422);
    }

}
