<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.id', 'orders.title', 'orders.cost', 'users.name')
            ->get();

        return view('orders.index', compact('orders'));
    }
}
