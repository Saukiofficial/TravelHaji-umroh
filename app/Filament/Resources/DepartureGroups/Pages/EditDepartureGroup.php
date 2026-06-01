<?php

namespace App\Filament\Resources\DepartureGroups\Pages;

use App\Filament\Resources\DepartureGroups\DepartureGroupResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditDepartureGroup extends EditRecord
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

            DeleteAction::make()
                ->label('Hapus'),
        ];
    }
}