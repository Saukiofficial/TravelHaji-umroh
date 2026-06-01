<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RegistrationParticipant extends Model
{
    protected $fillable = [
        'registration_id',
        'order_number',
        'name',
        'gender',
        'birth_date',
        'birth_place',
        'phone',
        'email',
        'nik',
        'passport_number',
        'passport_issued_at',
        'passport_expired_at',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'health_note',
        'note',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'passport_issued_at' => 'date',
        'passport_expired_at' => 'date',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class);
    }

    public function departureManifest(): HasOne
    {
        return $this->hasOne(DepartureGroupParticipant::class, 'registration_participant_id');
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender ?: '-';
    }

    public function getPassportStatusAttribute(): string
    {
        if (! $this->passport_number) {
            return 'Belum Ada Paspor';
        }

        if (! $this->passport_expired_at) {
            return 'Paspor Belum Lengkap';
        }

        if ($this->passport_expired_at->isPast()) {
            return 'Paspor Expired';
        }

        if ($this->passport_expired_at->diffInMonths(now()) < 7) {
            return 'Masa Berlaku Kurang dari 7 Bulan';
        }

        return 'Paspor Aman';
    }

    public function getDocumentCompletionStatusAttribute(): string
    {
        $requiredDocuments = [
            'ktp',
            'paspor',
            'kartu_keluarga',
        ];

        $validDocuments = $this->documents()
            ->whereIn('document_type', $requiredDocuments)
            ->where('status', 'valid')
            ->pluck('document_type')
            ->toArray();

        foreach ($requiredDocuments as $documentType) {
            if (! in_array($documentType, $validDocuments, true)) {
                return 'Belum Lengkap';
            }
        }

        return 'Lengkap';
    }

    public static function genderOptions(): array
    {
        return [
            'Laki-laki' => 'Laki-laki',
            'Perempuan' => 'Perempuan',
        ];
    }
}