<?php

namespace App\Filament\Resources\DepartureGroups\Tables;

use App\Models\DepartureGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DepartureGroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Grup')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (DepartureGroup $record): string => $record->code ?: 'Tanpa kode'),

                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => DepartureGroup::typeOptions()[$state] ?? '-')
                    ->color(fn (?string $state): string => match ($state) {
                        'umroh' => 'success',
                        'haji' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => DepartureGroup::statusOptions()[$state] ?? '-')
                    ->color(fn (?string $state): string => match ($state) {
                        'draft' => 'gray',
                        'open' => 'success',
                        'full' => 'warning',
                        'departed' => 'info',
                        'completed' => 'primary',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('package.title')
                    ->label('Paket')
                    ->limit(30)
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('departure_date')
                    ->label('Berangkat')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('return_date')
                    ->label('Pulang')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('airline')
                    ->label('Maskapai')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('seat_quota')
                    ->label('Kuota')
                    ->suffix(' Seat')
                    ->alignCenter(),

                TextColumn::make('used_seats')
                    ->label('Terisi')
                    ->suffix(' Jamaah')
                    ->alignCenter(),

                TextColumn::make('remaining_seats')
                    ->label('Sisa')
                    ->suffix(' Seat')
                    ->alignCenter()
                    ->color(fn (DepartureGroup $record): string => $record->remaining_seats <= 0 ? 'danger' : 'success'),

                TextColumn::make('tour_leader_name')
                    ->label('Tour Leader')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis')
                    ->options(DepartureGroup::typeOptions()),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(DepartureGroup::statusOptions()),
            ])
            ->actions([
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
            ->defaultSort('departure_date', 'desc');
    }
}