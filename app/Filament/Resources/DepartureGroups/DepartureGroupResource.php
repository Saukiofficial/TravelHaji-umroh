<?php

namespace App\Filament\Resources\DepartureGroups;

use App\Filament\Resources\DepartureGroups\Pages\CreateDepartureGroup;
use App\Filament\Resources\DepartureGroups\Pages\EditDepartureGroup;
use App\Filament\Resources\DepartureGroups\Pages\ListDepartureGroups;
use App\Filament\Resources\DepartureGroups\Pages\ViewDepartureGroup;
use App\Filament\Resources\DepartureGroups\Schemas\DepartureGroupForm;
use App\Filament\Resources\DepartureGroups\Tables\DepartureGroupsTable;
use App\Models\DepartureGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DepartureGroupResource extends Resource
{
    protected static ?string $model = DepartureGroup::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paper-airplane';

    protected static string|UnitEnum|null $navigationGroup = 'Operasional Keberangkatan';

    protected static ?string $navigationLabel = 'Manifest Keberangkatan';

    protected static ?string $modelLabel = 'Manifest Keberangkatan';

    protected static ?string $pluralModelLabel = 'Manifest Keberangkatan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return DepartureGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartureGroupsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'package',
                'participants',
                'participants.registration',
                'participants.participant',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDepartureGroups::route('/'),
            'create' => CreateDepartureGroup::route('/create'),
            'view' => ViewDepartureGroup::route('/{record}'),
            'edit' => EditDepartureGroup::route('/{record}/edit'),
        ];
    }
}