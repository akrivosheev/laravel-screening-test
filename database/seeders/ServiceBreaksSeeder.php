<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceBreak;

class ServiceBreaksSeeder extends Seeder
{
    public function run(): void
    {
        $men = Service::where('name', 'Men Haircut')->first();
        $women = Service::where('name', 'Women Haircut')->first();

        $services = [$men, $women];

        foreach ($services as $service) {
            // Lunch 12:00–13:00
            ServiceBreak::create([
                'service_id' => $service->id,
                'start_time' => '12:00',
                'end_time' => '13:00',
                'type' => 'lunch',
            ]);

            // Cleaning 15:00–16:00
            ServiceBreak::create([
                'service_id' => $service->id,
                'start_time' => '15:00',
                'end_time' => '16:00',
                'type' => 'cleaning',
            ]);
        }
    }
}
