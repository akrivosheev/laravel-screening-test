<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceBreak extends Model
{
    protected $fillable = [
        'service_id',
        'start_time',
        'end_time',
        'type',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
