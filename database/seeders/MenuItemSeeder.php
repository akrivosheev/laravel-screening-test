<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Seed menu items.
     */
    public function run(): void
    {
        $rootItem = MenuItem::create([
            'name' => 'All events',
            'url' => '/events',
        ]);

        $laraconItem = MenuItem::create([
            'name' => 'Laracon',
            'url' => '/events/laracon',
            'parent_id' => $rootItem->id,
        ]);

        MenuItem::create([
            'name' => 'Illuminate your knowledge of the laravel code base',
            'url' => '/events/laracon/workshops/illuminate',
            'parent_id' => $laraconItem->id,
        ]);

        MenuItem::create([
            'name' => 'The new Eloquent - load more with less',
            'url' => '/events/laracon/workshops/eloquent',
            'parent_id' => $laraconItem->id,
        ]);

        $reactconItem = MenuItem::create([
            'name' => 'Reactcon',
            'url' => '/events/reactcon',
            'parent_id' => $rootItem->id,
        ]);

        MenuItem::create([
            'name' => '#NoClass pure functional programming',
            'url' => '/events/reactcon/workshops/noclass',
            'parent_id' => $reactconItem->id,
        ]);

        MenuItem::create([
            'name' => 'Navigating the function jungle',
            'url' => '/events/reactcon/workshops/jungle',
            'parent_id' => $reactconItem->id,
        ]);
    }
}
