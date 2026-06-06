<?php

namespace App\Filament\Resources\WhatsAppBroadcasts\Pages;

use App\Filament\Resources\WhatsAppBroadcasts\WhatsAppBroadcastResource;
use App\Models\RegistrationParticipant;
use App\Models\WhatsAppBroadcastRecipient;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateWhatsAppBroadcast extends CreateRecord
{
    protected static string $resource = WhatsAppBroadcastResource::class;

    protected array $targetParticipantIds = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->targetParticipantIds = $data['target_participant_ids'] ?? [];

        unset($data['target_participant_ids']);

        $data['status'] = 'ready';

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        $this->syncRecipients($record);

        $record->forceFill([
            'total_recipients' => $record->recipients()->count(),
            'status' => 'ready',
        ])->save();

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return WhatsAppBroadcastResource::getUrl('view', [
            'record' => $this->record,
        ]);
    }

    private function syncRecipients(Model $record): void
    {
        $participants = RegistrationParticipant::query()
            ->with(['registration.package'])
            ->whereIn('id', $this->targetParticipantIds)
            ->get();

        foreach ($participants as $participant) {
            $phone = $participant->whatsapp_number;

            $message = $this->buildMessage(
                template: $record->message,
                participant: $participant,
            );

            WhatsAppBroadcastRecipient::query()->create([
                'whatsapp_broadcast_id' => $record->id,
                'registration_id' => $participant->registration_id,
                'registration_participant_id' => $participant->id,
                'recipient_name' => $participant->name ?: 'Jamaah',
                'recipient_phone' => $phone,
                'final_message' => $message,
                'wa_url' => $phone ? 'https://wa.me/' . $phone . '?text=' . rawurlencode($message) : null,
                'status' => 'ready',
            ]);
        }
    }

    private function buildMessage(string $template, RegistrationParticipant $participant): string
    {
        $participant->loadMissing(['registration.package']);

        $replacements = [
            '{nama}' => $participant->name ?: 'Bapak/Ibu Jamaah',
            '{paket}' => $participant->registration?->package?->title ?: '-',
            '{link_revisi}' => $participant->revision_url,
            '{whatsapp}' => $participant->whatsapp_number ?: '-',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
}