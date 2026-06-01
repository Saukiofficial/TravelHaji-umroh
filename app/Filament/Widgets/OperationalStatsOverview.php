<?php

namespace App\Filament\Widgets;

use App\Models\DepartureGroup;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Models\RegistrationPayment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationalStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Operasional Travel';

    protected function getStats(): array
    {
        $activeDepartureGroups = DepartureGroup::query()
            ->whereIn('status', ['open', 'full', 'departed'])
            ->count();

        $totalSeatQuota = DepartureGroup::query()
            ->whereIn('status', ['open', 'full'])
            ->sum('seat_quota');

        $usedSeats = DepartureGroup::query()
            ->whereIn('status', ['open', 'full'])
            ->withCount('participants')
            ->get()
            ->sum('participants_count');

        $remainingSeats = max((int) $totalSeatQuota - (int) $usedSeats, 0);

        return [
            Stat::make('Pendaftaran Baru', Registration::query()->where('status', 'baru')->count())
                ->description('Calon jamaah belum diproses')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('warning'),

            Stat::make('Dokumen Belum Valid', RegistrationDocument::query()->whereIn('status', ['belum_dicek', 'perlu_revisi', 'ditolak'])->count())
                ->description('Dokumen perlu dicek admin')
                ->descriptionIcon('heroicon-m-document-magnifying-glass')
                ->color('danger'),

            Stat::make('Pembayaran Menunggu', RegistrationPayment::query()->where('status', 'menunggu_verifikasi')->count())
                ->description('Pembayaran belum diverifikasi')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Pembayaran Valid', 'Rp ' . number_format(
                (float) RegistrationPayment::query()
                    ->where('status', 'valid')
                    ->whereIn('payment_type', ['dp', 'cicilan', 'pelunasan', 'tambahan'])
                    ->sum('amount'),
                0,
                ',',
                '.'
            ))
                ->description('Total pembayaran valid')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Manifest Aktif', $activeDepartureGroups)
                ->description('Grup open/full/departed')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('info'),

            Stat::make('Seat Terisi', $usedSeats . ' Jamaah')
                ->description('Total peserta di manifest aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Sisa Seat', $remainingSeats . ' Seat')
                ->description('Sisa kuota manifest aktif')
                ->descriptionIcon('heroicon-m-ticket')
                ->color($remainingSeats <= 0 ? 'danger' : 'success'),
        ];
    }
}