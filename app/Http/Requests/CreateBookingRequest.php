<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'clients' => 'required|array|min:1',
            'clients.*.first_name' => 'required|string',
            'clients.*.last_name' => 'required|string',
            'clients.*.email' => 'required|email',
        ];
    }
}
