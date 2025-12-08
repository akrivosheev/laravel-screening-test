<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $alice = User::where('name', 'Alice')->first();
        $bob   = User::where('name', 'Bob')->first();

        Order::create([
            'title' => 'Laptop',
            'cost' => 1200.00,
            'user_id' => $alice->id,
        ]);

        Order::create([
            'title' => 'Phone',
            'cost' => 800.00,
            'user_id' => $bob->id,
        ]);

        Order::create([
            'title' => 'Headphones',
            'cost' => 150.00,
            'user_id' => $bob->id,
        ]);
    }
}
