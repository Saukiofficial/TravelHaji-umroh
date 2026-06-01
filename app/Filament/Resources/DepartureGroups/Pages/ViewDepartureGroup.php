<?php

namespace App\Filament\Resources\DepartureGroups\Pages;

use App\Filament\Resources\DepartureGroups\DepartureGroupResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;

class ViewDepartureGroup extends ViewRecord
{
    protected static string $resource = DepartureGroupResource::class;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetak_manifest')
                ->label('Cetak Manifest')
                ->icon('heroicon-m-printer')
                ->color('success')
                ->url(fn () => route('admin.departure-groups.manifest-pdf', $this->record))
                ->openUrlInNewTab(),

            EditAction::make()
                ->label('Edit'),
        ];
    }
}