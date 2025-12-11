<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Workshop;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Seed events.
     */
    public function run(): void
    {
        $date = (new Carbon)->subYear()->setDay(21);

        $lcon1 = Event::create([
            'name' => 'Laravel convention '.$date->year,
        ]);

        Workshop::create([
            'start' => $date->clone()->setMonth(2)->setHour(10),
            'end' => $date->clone()->setMonth(2)->setHour(16),
            'name' => 'Illuminate your knowledge of the laravel code base',
            'event_id' => $lcon1->id,
        ]);

        $date = (new Carbon)->addYears(1);

        $lcon2 = Event::create([
            'name' => 'Laravel convention '.$date->year,
        ]);

        Workshop::create([
            'start' => $date->clone()->setMonth(10)->setHour(10),
            'end' => $date->clone()->setMonth(10)->setHour(16),
            'name' => 'The new Eloquent - load more with less',
            'event_id' => $lcon2->id,
        ]);

        Workshop::create([
            'start' => $date->clone()->setMonth(11)->setHour(10),
            'end' => $date->clone()->setMonth(11)->setHour(17),
            'name' => 'AutoEx - handles exceptions 100% automatic',
            'event_id' => $lcon2->id,
        ]);

        $rcon = Event::create([
            'name' => 'React convention '.$date->year,
        ]);

        Workshop::create([
            'start' => $date->clone()->setMonth(8)->setHour(10),
            'end' => $date->clone()->setMonth(8)->setHour(18),
            'name' => '#NoClass pure functional programming',
            'event_id' => $rcon->id,
        ]);

        Workshop::create([
            'start' => $date->clone()->setMonth(11)->setHour(9),
            'end' => $date->clone()->setMonth(11)->setHour(17),
            'name' => 'Navigating the function jungle',
            'event_id' => $rcon->id,
        ]);
    }
}
