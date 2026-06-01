<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartureGroupParticipant extends Model
{
    protected $fillable = [
        'departure_group_id',
        'registration_id',
        'registration_participant_id',
        'manifest_number',
        'baggage_number',
        'bus_number',
        'room_number',
        'room_type',
        'visa_status',
        'visa_number',
        'visa_issued_at',
        'ticket_status',
        'ticket_number',
        'booking_code',
        'departure_status',
        'notes',
    ];

    protected $casts = [
        'visa_issued_at' => 'date',
    ];

    public function departureGroup(): BelongsTo
    {
        return $this->belongsTo(DepartureGroup::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(RegistrationParticipant::class, 'registration_participant_id');
    }

    public static function roomTypeOptions(): array
    {
        return [
            'single' => 'Single',
            'double' => 'Double',
            'triple' => 'Triple',
            'quad' => 'Quad',
            'family' => 'Family',
        ];
    }

    public static function visaStatusOptions(): array
    {
        return [
            'belum_diajukan' => 'Belum Diajukan',
            'proses' => 'Proses',
            'terbit' => 'Terbit',
            'ditolak' => 'Ditolak',
        ];
    }

    public static function ticketStatusOptions(): array
    {
        return [
            'belum_dipesan' => 'Belum Dipesan',
            'proses' => 'Proses',
            'issued' => 'Issued',
            'cancelled' => 'Cancelled',
        ];
    }

    public static function departureStatusOptions(): array
    {
        return [
            'terdaftar' => 'Terdaftar',
            'siap_berangkat' => 'Siap Berangkat',
            'berangkat' => 'Berangkat',
            'selesai' => 'Selesai',
            'batal' => 'Batal',
        ];
    }
}