<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RegistrationParticipant extends Model
{
    protected $fillable = [
        'registration_id',
        'order_number',

        'name',
        'gender',
        'birth_place',
        'birth_date',
        'nik',
        'phone',
        'email',
        'address',

        'passport_number',
        'passport_issued_at',
        'passport_expired_at',

        'emergency_contact_name',
        'emergency_contact_phone',
        'health_note',
        'note',

        'revision_token',
        'revision_token_created_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'passport_issued_at' => 'date',
        'passport_expired_at' => 'date',
        'revision_token_created_at' => 'datetime',
    ];

    protected $appends = [
        'revision_url',
    ];

    protected static function booted(): void
    {
        static::creating(function (RegistrationParticipant $participant) {
            if (! $participant->revision_token) {
                $participant->revision_token = Str::random(64);
                $participant->revision_token_created_at = now();
            }
        });
    }

    public function whatsappBroadcastRecipients(): HasMany
{
    return $this->hasMany(WhatsAppBroadcastRecipient::class, 'registration_participant_id');
}

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class, 'registration_participant_id');
    }

    public function documentRevisions(): HasMany
    {
        return $this->hasMany(RegistrationDocumentRevision::class, 'registration_participant_id');
    }

    public function departureGroupParticipants(): HasMany
    {
        return $this->hasMany(DepartureGroupParticipant::class, 'registration_participant_id');
    }

    public function ensureRevisionToken(): string
    {
        if (! $this->revision_token) {
            $this->forceFill([
                'revision_token' => Str::random(64),
                'revision_token_created_at' => now(),
            ])->save();
        }

        return $this->revision_token;
    }

    public function regenerateRevisionToken(): string
    {
        $this->forceFill([
            'revision_token' => Str::random(64),
            'revision_token_created_at' => now(),
        ])->save();

        return $this->revision_token;
    }

    public function getRevisionUrlAttribute(): string
    {
        return url('/revisi-dokumen/' . $this->ensureRevisionToken());
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: 'Peserta Jamaah';
    }

    public function getWhatsappNumberAttribute(): ?string
    {
        if ($this->phone) {
            return $this->normalizeWhatsappNumber($this->phone);
        }

        if ($this->registration?->phone) {
            return $this->normalizeWhatsappNumber($this->registration->phone);
        }

        return null;
    }

    private function normalizeWhatsappNumber(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $number = preg_replace('/[^0-9]/', '', $number);

        if (! $number) {
            return null;
        }

        if (str_starts_with($number, '08')) {
            return '62' . substr($number, 1);
        }

        if (str_starts_with($number, '8')) {
            return '62' . $number;
        }

        return $number;
    }
}