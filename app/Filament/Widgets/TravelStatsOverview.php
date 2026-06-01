<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Registration;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TravelStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Website Travel';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Paket', Package::query()->count())
                ->description('Semua paket umroh & haji')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('success'),

            Stat::make('Paket Published', Package::query()->where('status', 'published')->count())
                ->description('Paket tampil di website')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Pendaftaran', Registration::query()->count())
                ->description('Seluruh data calon jamaah')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Pendaftaran Baru', Registration::query()->where('status', 'baru')->count())
                ->description('Calon jamaah belum diproses')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color('warning'),

            Stat::make('Artikel Published', Article::query()->where('status', 'published')->count())
                ->description('Artikel aktif di website')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),

            Stat::make('Galeri Aktif', Gallery::query()->where('is_active', true)->count())
                ->description('Dokumentasi tampil di website')
                ->descriptionIcon('heroicon-m-photo')
                ->color('primary'),

            Stat::make('Testimoni Aktif', Testimonial::query()->where('is_active', true)->count())
                ->description('Testimoni tampil di website')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('success'),
        ];
    }
}