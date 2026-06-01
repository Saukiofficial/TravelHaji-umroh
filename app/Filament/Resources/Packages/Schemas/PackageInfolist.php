<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PackageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('price')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('duration_days')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('departure_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('airline')
                    ->placeholder('-'),
                TextEntry::make('makkah_hotel')
                    ->placeholder('-'),
                TextEntry::make('madinah_hotel')
                    ->placeholder('-'),
                TextEntry::make('seat')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('facilities')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('itinerary')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('requirements')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('image')
                    ->placeholder('-'),
                IconEntry::make('is_featured')
                    ->boolean(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
