<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingClient;
use App\Models\Service;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function createBooking(int $serviceId, int $slotId, array $clients): Booking
    {
        $service = Service::findOrFail($serviceId);
        $slot = TimeSlot::where('service_id', $serviceId)->findOrFail($slotId);

        // 1. Check: if slot avialable
        if (!$slot->is_available) {
            throw ValidationException::withMessages([
                'slot' => 'This slot is not available.',
            ]);
        }

        // 2. Check: if slot not in past
        if ($slot->start_datetime->isPast()) {
            throw ValidationException::withMessages([
                'slot' => 'Cannot book a past slot.',
            ]);
        }

        // 3. Check: client limit
        $alreadyBooked = $slot->clientsCount();
        $requested = count($clients);

        if ($alreadyBooked + $requested > $service->max_clients_per_slot) {
            throw ValidationException::withMessages([
                'slot' => 'Slot is fully booked.',
            ]);
        }

        // 4. Create booking
        $booking = Booking::create([
            'service_id' => $serviceId,
            'time_slot_id' => $slotId,
        ]);

        // 5. Add client
        foreach ($clients as $client) {
            BookingClient::create([
                'booking_id' => $booking->id,
                'first_name' => $client['first_name'],
                'last_name' => $client['last_name'],
                'email' => $client['email'],
            ]);
        }

        return $booking;
    }
}
