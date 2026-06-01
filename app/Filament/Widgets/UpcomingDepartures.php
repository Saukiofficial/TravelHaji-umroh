<?php

namespace App\Filament\Widgets;

use App\Models\DepartureGroup;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingDepartures extends BaseWidget
{
    protected static ?string $heading = 'Keberangkatan Terdekat';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DepartureGroup::query()
                    ->with(['package'])
                    ->whereIn('status', ['open', 'full'])
                    ->whereDate('departure_date', '>=', now()->toDateString())
                    ->orderBy('departure_date')
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Grup Keberangkatan')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn (DepartureGroup $record): string => $record->code ?: 'Tanpa kode'),

                TextColumn::make('package.title')
                    ->label('Paket')
                    ->placeholder('-')
                    ->limit(35),

                TextColumn::make('departure_date')
                    ->label('Berangkat')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('return_date')
                    ->label('Pulang')
                    ->date('d M Y'),

                TextColumn::make('airline')
                    ->label('Maskapai')
                    ->placeholder('-'),

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

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => DepartureGroup::statusOptions()[$state] ?? '-')
                    ->color(fn (?string $state): string => match ($state) {
                        'open' => 'success',
                        'full' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (DepartureGroup $record): string => url('/admin/departure-groups/' . $record->id))
                    ->openUrlInNewTab(false),

                Action::make('cetak_manifest')
                    ->label('Cetak')
                    ->icon('heroicon-m-printer')
                    ->color('success')
                    ->url(fn (DepartureGroup $record): string => route('admin.departure-groups.manifest-pdf', $record))
                    ->openUrlInNewTab(),
            ])
            ->paginated(false);
    }
}