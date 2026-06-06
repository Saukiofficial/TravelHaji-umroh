<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppBroadcastRecipient extends Model
{
    protected $table = 'whatsapp_broadcast_recipients';

    protected $fillable = [
        'whatsapp_broadcast_id',
        'registration_id',
        'registration_participant_id',
        'recipient_name',
        'recipient_phone',
        'final_message',
        'wa_url',
        'status',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function broadcast(): BelongsTo
    {
        return $this->belongsTo(WhatsAppBroadcast::class, 'whatsapp_broadcast_id');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(RegistrationParticipant::class, 'registration_participant_id');
    }

    public function markClicked(): void
    {
        $this->forceFill([
            'status' => 'clicked',
            'clicked_at' => now(),
        ])->save();
    }

    public static function statusOptions(): array
    {
        return [
            'ready' => 'Siap Dikirim',
            'clicked' => 'Sudah Dibuka',
            'failed' => 'Gagal',
        ];
    }
}