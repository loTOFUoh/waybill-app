<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waybill extends Model
{
    protected $fillable = [
        'number', 'user_id', 'driver_id', 'vehicle_id',
        'start_km', 'end_km', 'fuel_start', 'fuel_end',
        'fuel_consumed', 'status'
    ];

    // Добавляем ": BelongsTo" к каждому методу
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
