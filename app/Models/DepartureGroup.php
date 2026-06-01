<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DepartureGroup extends Model
{
    protected $fillable = [
        'package_id',
        'name',
        'code',
        'type',
        'status',
        'departure_date',
        'return_date',
        'departure_airport',
        'arrival_airport',
        'airline',
        'departure_flight_number',
        'return_flight_number',
        'departure_time',
        'return_time',
        'makkah_hotel',
        'madinah_hotel',
        'tour_leader_name',
        'tour_leader_phone',
        'muthawif_name',
        'muthawif_phone',
        'seat_quota',
        'meeting_point',
        'notes',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'departure_time' => 'datetime',
        'return_time' => 'datetime',
        'seat_quota' => 'integer',
    ];

    protected $appends = [
        'used_seats',
        'remaining_seats',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(DepartureGroupParticipant::class);
    }

    public function getUsedSeatsAttribute(): int
    {
        if ($this->relationLoaded('participants')) {
            return $this->participants->count();
        }

        return $this->participants()->count();
    }

    public function getRemainingSeatsAttribute(): int
    {
        return max((int) $this->seat_quota - $this->used_seats, 0);
    }

    public static function typeOptions(): array
    {
        return [
            'umroh' => 'Umroh',
            'haji' => 'Haji',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'open' => 'Dibuka',
            'full' => 'Penuh',
            'departed' => 'Sudah Berangkat',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    }
}