<?php

namespace App\Filament\Resources\WhatsAppBroadcasts;

use App\Filament\Resources\WhatsAppBroadcasts\Pages\CreateWhatsAppBroadcast;
use App\Filament\Resources\WhatsAppBroadcasts\Pages\EditWhatsAppBroadcast;
use App\Filament\Resources\WhatsAppBroadcasts\Pages\ListWhatsAppBroadcasts;
use App\Filament\Resources\WhatsAppBroadcasts\Pages\ViewWhatsAppBroadcast;
use App\Filament\Resources\WhatsAppBroadcasts\Schemas\WhatsAppBroadcastForm;
use App\Filament\Resources\WhatsAppBroadcasts\Schemas\WhatsAppBroadcastInfolist;
use App\Filament\Resources\WhatsAppBroadcasts\Tables\WhatsAppBroadcastsTable;
use App\Models\WhatsAppBroadcast;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class WhatsAppBroadcastResource extends Resource
{
    protected static ?string $model = WhatsAppBroadcast::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi Jamaah';

    protected static ?string $navigationLabel = 'WhatsApp Broadcast';

    protected static ?string $modelLabel = 'WhatsApp Broadcast';

    protected static ?string $pluralModelLabel = 'WhatsApp Broadcast';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return WhatsAppBroadcastForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WhatsAppBroadcastInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WhatsAppBroadcastsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'recipients',
                'recipients.registration',
                'recipients.participant',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWhatsAppBroadcasts::route('/'),
            'create' => CreateWhatsAppBroadcast::route('/create'),
            'view' => ViewWhatsAppBroadcast::route('/{record}'),
            'edit' => EditWhatsAppBroadcast::route('/{record}/edit'),
        ];
    }
}