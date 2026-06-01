<?php

namespace App\Filament\Resources\Registrations\Pages;

use App\Filament\Resources\Registrations\RegistrationResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;

class ViewRegistration extends ViewRecord
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

            EditAction::make()
                ->label('Edit'),
        ];
    }
}