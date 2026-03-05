<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waybill extends Model
{
    protected $fillable = [
        'number', 'user_id', 'driver_id', 'vehicle_id',
        'departure_time', 'return_time', 'route', 'cargo_info',
        'start_km', 'end_km', 'fuel_start', 'fuel_added', 'fuel_end',
        'fuel_consumed', 'mechanic_name', 'medic_name', 'status'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'return_time' => 'datetime',
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
