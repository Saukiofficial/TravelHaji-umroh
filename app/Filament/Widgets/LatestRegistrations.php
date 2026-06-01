<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestRegistrations extends BaseWidget
{
    protected static ?string $heading = 'Pendaftaran Jamaah Terbaru';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Registration::query()
                    ->with('package')
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Jamaah')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Registration $record): string => $record->phone ?? '-'),

                TextColumn::make('package.title')
                    ->label('Paket')
                    ->placeholder('Belum pilih paket')
                    ->limit(35),

                TextColumn::make('total_participants')
                    ->label('Peserta')
                    ->suffix(' Orang')
                    ->alignCenter(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'baru' => 'Baru',
                        'dihubungi' => 'Dihubungi',
                        'proses' => 'Proses',
                        'selesai' => 'Selesai',
                        'batal' => 'Batal',
                        default => ucfirst($state ?? '-'),
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'baru' => 'warning',
                        'dihubungi' => 'info',
                        'proses' => 'primary',
                        'selesai' => 'success',
                        'batal' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Registration $record): string => url('/admin/registrations/' . $record->id))
                    ->openUrlInNewTab(false),
            ])
            ->paginated(false);
    }
}