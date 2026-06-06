<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppBroadcast extends Model
{
    protected $table = 'whatsapp_broadcasts';

    protected $fillable = [
        'title',
        'message',
        'status',
        'total_recipients',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function recipients(): HasMany
    {
        return $this->hasMany(WhatsAppBroadcastRecipient::class, 'whatsapp_broadcast_id');
    }

    public function markAsReady(): void
    {
        $this->forceFill([
            'status' => 'ready',
            'total_recipients' => $this->recipients()->count(),
        ])->save();
    }

    public function markAsSent(): void
    {
        $this->forceFill([
            'status' => 'sent',
            'sent_at' => now(),
            'total_recipients' => $this->recipients()->count(),
        ])->save();
    }

    public static function statusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'ready' => 'Siap Dikirim',
            'sent' => 'Selesai',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? 'Draft';
    }
}