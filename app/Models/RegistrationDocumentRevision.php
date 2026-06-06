<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationDocumentRevision extends Model
{
    protected $fillable = [
        'registration_id',
        'registration_participant_id',
        'document_type',
        'document_label',
        'old_file_path',
        'new_file_path',
        'old_status',
        'new_status',
        'admin_note',
        'jamaah_note',
        'revision_number',
        'submitted_at',
        'verified_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(RegistrationParticipant::class, 'registration_participant_id');
    }

    public function getDocumentLabelValueAttribute(): string
    {
        return $this->document_label ?: $this->getDefaultDocumentLabel($this->document_type);
    }

    private function getDefaultDocumentLabel(?string $type): string
    {
        return match ($type) {
            'ktp' => 'KTP / Identitas',
            'paspor' => 'Paspor',
            'kartu_keluarga' => 'Kartu Keluarga',
            'buku_nikah_akta_ijazah' => 'Buku Nikah / Akta / Ijazah',
            'pas_foto' => 'Pas Foto',
            'sertifikat_vaksin' => 'Sertifikat Vaksin',
            'dokumen_tambahan' => 'Dokumen Tambahan',
            default => 'Dokumen',
        };
    }
}