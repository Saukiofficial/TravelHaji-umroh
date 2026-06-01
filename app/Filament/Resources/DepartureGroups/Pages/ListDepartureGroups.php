<?php

namespace App\Filament\Resources\DepartureGroups\Pages;

use App\Filament\Resources\DepartureGroups\DepartureGroupResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDepartureGroups extends ListRecords
{
    protected static string $resource = DepartureGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_manifest')
                ->label('Export Manifest')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('success')
                ->url(fn () => route('admin.export.manifests'))
                ->openUrlInNewTab(),

            CreateAction::make()
                ->label('Tambah Manifest'),
        ];
    }
}