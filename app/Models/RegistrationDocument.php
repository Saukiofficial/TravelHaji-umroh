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
}