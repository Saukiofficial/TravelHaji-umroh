<?php

namespace App\Filament\Resources\WhatsAppBroadcasts\Pages;

use App\Filament\Resources\WhatsAppBroadcasts\WhatsAppBroadcastResource;
use App\Models\RegistrationParticipant;
use App\Models\WhatsAppBroadcastRecipient;
use Filament\Resources\Pages\EditRecord;

class EditWhatsAppBroadcast extends EditRecord
{
    protected static string $resource = WhatsAppBroadcastResource::class;

    protected array $targetParticipantIds = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->loadMissing('recipients');

        $data['target_participant_ids'] = $this->record->recipients
            ->pluck('registration_participant_id')
            ->filter()
            ->values()
            ->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->targetParticipantIds = $data['target_participant_ids'] ?? [];

        unset($data['target_participant_ids']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->recipients()->delete();

        $participants = RegistrationParticipant::query()
            ->with(['registration.package'])
            ->whereIn('id', $this->targetParticipantIds)
            ->get();

        foreach ($participants as $participant) {
            $phone = $participant->whatsapp_number;

            $message = $this->buildMessage(
                template: $this->record->message,
                participant: $participant,
            );

            WhatsAppBroadcastRecipient::query()->create([
                'whatsapp_broadcast_id' => $this->record->id,
                'registration_id' => $participant->registration_id,
                'registration_participant_id' => $participant->id,
                'recipient_name' => $participant->name ?: 'Jamaah',
                'recipient_phone' => $phone,
                'final_message' => $message,
                'wa_url' => $phone ? 'https://wa.me/' . $phone . '?text=' . rawurlencode($message) : null,
                'status' => 'ready',
            ]);
        }

        $this->record->forceFill([
            'status' => 'ready',
            'total_recipients' => $this->record->recipients()->count(),
        ])->save();
    }

    protected function getRedirectUrl(): string
    {
        return WhatsAppBroadcastResource::getUrl('view', [
            'record' => $this->record,
        ]);
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