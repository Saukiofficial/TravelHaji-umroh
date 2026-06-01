<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'type',
        'title',
        'slug',
        'price',
        'duration_days',
        'departure_date',
        'airline',
        'makkah_hotel',
        'madinah_hotel',
        'seat',
        'facilities',
        'itinerary',
        'requirements',
        'description',
        'image',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'departure_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}