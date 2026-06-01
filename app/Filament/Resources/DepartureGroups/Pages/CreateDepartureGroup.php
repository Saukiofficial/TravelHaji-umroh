<?php

namespace App\Filament\Resources\DepartureGroups\Pages;

use App\Filament\Resources\DepartureGroups\DepartureGroupResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateDepartureGroup extends CreateRecord
{
    protected static string $resource = DepartureGroupResource::class;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}