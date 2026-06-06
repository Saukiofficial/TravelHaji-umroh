<?php

namespace App\Filament\Resources\WhatsAppBroadcasts\Tables;

use App\Filament\Resources\WhatsAppBroadcasts\WhatsAppBroadcastResource;
use App\Models\WhatsAppBroadcast;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WhatsAppBroadcastsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Broadcast')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn (WhatsAppBroadcast $record): string => match ($record->status) {
                        'ready' => 'warning',
                        'sent' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('total_recipients')
                    ->label('Penerima')
                    ->numeric()
                    ->suffix(' Jamaah')
                    ->sortable(),

                TextColumn::make('sent_at')
                    ->label('Selesai Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn (WhatsAppBroadcast $record): string => WhatsAppBroadcastResource::getUrl('view', ['record' => $record]))
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}