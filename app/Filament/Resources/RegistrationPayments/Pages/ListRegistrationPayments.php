<?php

namespace App\Filament\Resources\RegistrationPayments\Pages;

use App\Filament\Resources\RegistrationPayments\RegistrationPaymentResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegistrationPayments extends ListRecords
{
    protected static string $resource = RegistrationPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_pembayaran')
                ->label('Export Pembayaran')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('success')
                ->url(fn () => route('admin.export.payments'))
                ->openUrlInNewTab(),

            CreateAction::make()
                ->label('Tambah Pembayaran'),
        ];
    }
}