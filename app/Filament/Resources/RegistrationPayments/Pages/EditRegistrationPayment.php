<?php

namespace App\Filament\Resources\RegistrationPayments\Pages;

use App\Filament\Resources\RegistrationPayments\RegistrationPaymentResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditRegistrationPayment extends EditRecord
{
    protected static string $resource = RegistrationPaymentResource::class;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetak_kwitansi')
                ->label('Cetak Kwitansi')
                ->icon('heroicon-m-printer')
                ->color('success')
                ->url(fn () => route('admin.payments.receipt-pdf', $this->record))
                ->openUrlInNewTab(),

            DeleteAction::make()
                ->label('Hapus'),
        ];
    }
}