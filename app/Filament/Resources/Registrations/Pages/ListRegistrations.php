<?php

namespace App\Filament\Resources\Registrations\Pages;

use App\Filament\Resources\Registrations\RegistrationResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_pendaftaran')
                ->label('Export Pendaftaran')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('success')
                ->url(fn () => route('admin.export.registrations'))
                ->openUrlInNewTab(),

            CreateAction::make()
                ->label('Tambah Pendaftaran'),
        ];
    }
}