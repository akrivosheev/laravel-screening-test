<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceWorkingHour;
use App\Models\ServiceBreak;
use App\Models\ServiceOffDate;
use App\Models\TimeSlot;
use Carbon\Carbon;

class TimeSlotsSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all();

        foreach ($services as $service) {
            $daysAhead = $service->bookable_days_ahead;

            for ($i = 0; $i < $daysAhead; $i++) {
                $date = Carbon::now()->startOfDay()->addDays($i);
                $weekday = $date->dayOfWeekIso; // 1–7

                $workingHours = ServiceWorkingHour::where('service_id', $service->id)
                    ->where('weekday', $weekday)
                    ->first();

                if (!$workingHours) {
                    continue; // выходной
                }

                $off = ServiceOffDate::where('service_id', $service->id)
                    ->where('start_datetime', '<=', $date->copy()->endOfDay())
                    ->where('end_datetime', '>=', $date->copy()->startOfDay())
                    ->exists();

                if ($off) {
                    continue;
                }

                $start = Carbon::parse($date->toDateString() . ' ' . $workingHours->start_time);
                $end = Carbon::parse($date->toDateString() . ' ' . $workingHours->end_time);

                $breaks = ServiceBreak::where('service_id', $service->id)->get();

                while ($start->copy()->addMinutes($service->slot_duration_minutes) <= $end) {

                    $slotStart = $start->copy();
                    $slotEnd = $start->copy()->addMinutes($service->slot_duration_minutes);

                    $inBreak = $breaks->contains(function ($br) use ($slotStart, $slotEnd, $date) {
                        $brStart = Carbon::parse($date->toDateString() . ' ' . $br->start_time);
                        $brEnd = Carbon::parse($date->toDateString() . ' ' . $br->end_time);
                        return $slotStart < $brEnd && $slotEnd > $brStart;
                    });

                    if (!$inBreak) {
                        TimeSlot::create([
                            'service_id' => $service->id,
                            'start_datetime' => $slotStart,
                            'end_datetime' => $slotEnd,
                            'is_available' => true,
                        ]);
                    }

                    $start->addMinutes($service->slot_interval_minutes + $service->cleanup_break_minutes);
                }
            }
        }
    }
}
