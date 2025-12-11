<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceOffDate;
use Carbon\Carbon;

class ServiceOffDatesSeeder extends Seeder
{
    public function run(): void
    {
        $men = Service::where('name', 'Men Haircut')->first();
        $women = Service::where('name', 'Women Haircut')->first();

        $offDay = Carbon::now()->addDays(2)->startOfDay(); // 3-й день

        foreach ([$men, $women] as $service) {
            ServiceOffDate::create([
                'service_id' => $service->id,
                'start_datetime' => $offDay->copy(),
                'end_datetime' => $offDay->copy()->endOfDay(),
                'reason' => 'Public holiday',
            ]);
        }
    }
}
