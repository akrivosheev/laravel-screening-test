<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceWorkingHour;

class ServiceWorkingHoursSeeder extends Seeder
{
    public function run(): void
    {
        $men = Service::where('name', 'Men Haircut')->first();
        $women = Service::where('name', 'Women Haircut')->first();

        foreach ([1,2,3,4,5] as $day) {
            ServiceWorkingHour::create([
                'service_id' => $men->id,
                'weekday' => $day,
                'start_time' => '08:00',
                'end_time' => '20:00',
            ]);

            ServiceWorkingHour::create([
                'service_id' => $women->id,
                'weekday' => $day,
                'start_time' => '08:00',
                'end_time' => '20:00',
            ]);
        }

        ServiceWorkingHour::create([
            'service_id' => $men->id,
            'weekday' => 6,
            'start_time' => '10:00',
            'end_time' => '22:00',
        ]);

        ServiceWorkingHour::create([
            'service_id' => $women->id,
            'weekday' => 6,
            'start_time' => '10:00',
            'end_time' => '22:00',
        ]);

    }
}
