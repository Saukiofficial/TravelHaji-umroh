<?php

namespace App\Filament\Resources\Registrations\Pages;

use App\Filament\Resources\Registrations\RegistrationResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditRegistration extends EditRecord
{
    protected static string $resource = RegistrationResource::class;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetak_berkas')
                ->label('Cetak Berkas')
                ->icon('heroicon-m-printer')
                ->color('success')
                ->url(fn () => route('admin.registrations.bundle-pdf', $this->record))
                ->openUrlInNewTab(),

            DeleteAction::make()
                ->label('Hapus'),
        ];
    }
}