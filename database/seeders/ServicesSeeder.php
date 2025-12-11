<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        Service::create([
            'name' => 'Men Haircut',
            'slot_duration_minutes' => 30,
            'slot_interval_minutes' => 10,
            'cleanup_break_minutes' => 5,
            'max_clients_per_slot' => 3,
            'bookable_days_ahead' => 7,
        ]);

        Service::create([
            'name' => 'Women Haircut',
            'slot_duration_minutes' => 60,
            'slot_interval_minutes' => 60,
            'cleanup_break_minutes' => 10,
            'max_clients_per_slot' => 3,
            'bookable_days_ahead' => 7,
        ]);
    }
}
