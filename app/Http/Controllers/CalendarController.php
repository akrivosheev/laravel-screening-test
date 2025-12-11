<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller
{
    public function index(): JsonResponse
    {
        $services = Service::with([
            'workingHours',
            'breaks',
            'offDates',
            'timeSlots' => function ($q) {
                $q->orderBy('start_datetime');
            }
        ])->get();

        return response()->json([
            'services' => $services,
        ]);
    }
}
