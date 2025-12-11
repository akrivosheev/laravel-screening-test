<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookingRequest;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function store(CreateBookingRequest $request, BookingService $service): JsonResponse
    {
        $booking = $service->createBooking(
            $request->service_id,
            $request->time_slot_id,
            $request->clients
        );

        return response()->json([
            'status' => 'success',
            'booking' => $booking->load('clients', 'timeSlot'),
        ], 201);
    }
}
