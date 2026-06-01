<?php

namespace App\Filament\Resources\RegistrationPayments\Tables;

use App\Models\RegistrationPayment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RegistrationPaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payment_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->placeholder('-'),

                TextColumn::make('registration.name')
                    ->label('Pendaftar')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (RegistrationPayment $record): string => $record->registration?->package?->title ?: 'Tanpa paket'),

                TextColumn::make('payment_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => RegistrationPayment::paymentTypeOptions()[$state] ?? '-')
                    ->color(fn (?string $state): string => match ($state) {
                        'dp' => 'info',
                        'cicilan' => 'warning',
                        'pelunasan' => 'success',
                        'tambahan' => 'gray',
                        'refund' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->formatStateUsing(fn (?string $state): string => RegistrationPayment::paymentMethodOptions()[$state] ?? '-')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => RegistrationPayment::statusOptions()[$state] ?? '-')
                    ->color(fn (?string $state): string => match ($state) {
                        'menunggu_verifikasi' => 'warning',
                        'valid' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('paid_at')
                    ->label('Tanggal Bayar')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('payment_type')
                    ->label('Jenis Pembayaran')
                    ->options(RegistrationPayment::paymentTypeOptions()),

                SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options(RegistrationPayment::paymentMethodOptions()),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(RegistrationPayment::statusOptions()),
            ])
            ->actions([
                Action::make('cetak_kwitansi')
                    ->label('Kwitansi')
                    ->icon('heroicon-m-printer')
                    ->color('success')
                    ->url(fn (RegistrationPayment $record): string => route('admin.payments.receipt-pdf', $record))
                    ->openUrlInNewTab(),

                ViewAction::make()
                    ->label('View'),

                EditAction::make()
                    ->label('Edit'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}