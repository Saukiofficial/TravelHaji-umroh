<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
    protected $fillable = [
        'package_id',
        'name',
        'phone',
        'email',
        'address',
        'gender',
        'birth_date',
        'total_participants',
        'note',
        'document_file',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected $appends = [
        'total_bill',
        'total_paid',
        'total_refund',
        'remaining_payment',
        'payment_status',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(RegistrationParticipant::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(RegistrationPayment::class);
    }

    public function getTotalBillAttribute(): float
    {
        $packagePrice = (float) ($this->package?->price ?? 0);
        $participants = (int) ($this->total_participants ?? 1);

        return $packagePrice * max($participants, 1);
    }

    public function getTotalPaidAttribute(): float
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments
                ->where('status', 'valid')
                ->whereIn('payment_type', ['dp', 'cicilan', 'pelunasan', 'tambahan'])
                ->sum('amount');
        }

        return (float) $this->payments()
            ->where('status', 'valid')
            ->whereIn('payment_type', ['dp', 'cicilan', 'pelunasan', 'tambahan'])
            ->sum('amount');
    }

    public function getTotalRefundAttribute(): float
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments
                ->where('status', 'valid')
                ->where('payment_type', 'refund')
                ->sum('amount');
        }

        return (float) $this->payments()
            ->where('status', 'valid')
            ->where('payment_type', 'refund')
            ->sum('amount');
    }

    public function getRemainingPaymentAttribute(): float
    {
        return max($this->total_bill - $this->total_paid + $this->total_refund, 0);
    }

    public function getPaymentStatusAttribute(): string
    {
        if ($this->total_bill <= 0) {
            return 'belum_ada_tagihan';
        }

        if ($this->total_paid <= 0) {
            return 'belum_bayar';
        }

        if ($this->remaining_payment <= 0) {
            return 'lunas';
        }

        return 'sebagian';
    }

    public static function statusOptions(): array
    {
        return [
            'baru' => 'Baru',
            'dihubungi' => 'Dihubungi',
            'proses' => 'Proses',
            'dokumen_lengkap' => 'Dokumen Lengkap',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'selesai' => 'Selesai',
            'batal' => 'Batal',
        ];
    }

    public static function paymentStatusOptions(): array
    {
        return [
            'belum_ada_tagihan' => 'Belum Ada Tagihan',
            'belum_bayar' => 'Belum Bayar',
            'sebagian' => 'Bayar Sebagian',
            'lunas' => 'Lunas',
        ];
    }
}