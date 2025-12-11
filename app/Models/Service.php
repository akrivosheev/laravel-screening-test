<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slot_duration_minutes',
        'slot_interval_minutes',
        'cleanup_break_minutes',
        'max_clients_per_slot',
        'bookable_days_ahead',
    ];

    public function workingHours(): HasMany
    {
        return $this->hasMany(ServiceWorkingHour::class);
    }

    public function breaks(): HasMany
    {
        return $this->hasMany(ServiceBreak::class);
    }

    public function offDates(): HasMany
    {
        return $this->hasMany(ServiceOffDate::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
