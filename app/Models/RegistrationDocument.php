<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationDocument extends Model
{
    protected $fillable = [
        'registration_id',
        'registration_participant_id',
        'document_type',
        'file_path',
        'status',
        'note',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    protected $appends = [
        'document_label_value',
        'status_label_value',
        'status_color_value',
        'status_bg_value',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(RegistrationParticipant::class, 'registration_participant_id');
    }

    public static function documentTypeOptions(): array
    {
        return [
            'ktp' => 'KTP / Identitas',
            'paspor' => 'Paspor',
            'kartu_keluarga' => 'Kartu Keluarga',
            'buku_nikah_akta_ijazah' => 'Buku Nikah / Akta / Ijazah',
            'pas_foto' => 'Pas Foto',
            'sertifikat_vaksin' => 'Sertifikat Vaksin',
            'dokumen_tambahan' => 'Dokumen Tambahan',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'belum_dicek' => 'Belum Dicek',
            'valid' => 'Valid',
            'perlu_revisi' => 'Perlu Revisi',
            'ditolak' => 'Ditolak',
        ];
    }

    public function getDocumentLabelValueAttribute(): string
    {
        return self::documentTypeOptions()[$this->document_type] ?? 'Dokumen';
    }

    public function getStatusLabelValueAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? 'Belum Dicek';
    }

    public function getStatusColorValueAttribute(): string
    {
        return match ($this->status) {
            'valid' => '#22c55e',
            'perlu_revisi' => '#f59e0b',
            'ditolak' => '#ef4444',
            'belum_dicek' => '#38bdf8',
            default => '#a1a1aa',
        };
    }

    public function getStatusBgValueAttribute(): string
    {
        return match ($this->status) {
            'valid' => 'rgba(34, 197, 94, 0.12)',
            'perlu_revisi' => 'rgba(245, 158, 11, 0.12)',
            'ditolak' => 'rgba(239, 68, 68, 0.12)',
            'belum_dicek' => 'rgba(56, 189, 248, 0.12)',
            default => 'rgba(161, 161, 170, 0.12)',
        };
    }

    public function needsRevision(): bool
    {
        return in_array($this->status, ['perlu_revisi', 'ditolak'], true);
    }
}