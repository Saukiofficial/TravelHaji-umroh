<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationPayment extends Model
{
    protected $fillable = [
        'registration_id',
        'payment_code',
        'payment_type',
        'amount',
        'payment_method',
        'paid_at',
        'proof_file',
        'status',
        'note',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'date',
        'verified_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public static function paymentTypeOptions(): array
    {
        return [
            'dp' => 'DP / Uang Muka',
            'cicilan' => 'Cicilan',
            'pelunasan' => 'Pelunasan',
            'tambahan' => 'Biaya Tambahan',
            'refund' => 'Refund / Pengembalian',
        ];
    }

    public static function paymentMethodOptions(): array
    {
        return [
            'transfer_bank' => 'Transfer Bank',
            'cash' => 'Cash / Tunai',
            'qris' => 'QRIS',
            'lainnya' => 'Lainnya',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'valid' => 'Valid',
            'ditolak' => 'Ditolak',
        ];
    }
}