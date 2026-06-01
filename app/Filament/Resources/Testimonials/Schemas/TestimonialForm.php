<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Jamaah')
                    ->description('Masukkan informasi jamaah yang memberikan testimoni.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Jamaah')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('city')
                            ->label('Kota Asal')
                            ->placeholder('Contoh: Surabaya')
                            ->maxLength(255),

                        TextInput::make('package_name')
                            ->label('Nama Paket')
                            ->placeholder('Contoh: Umroh Reguler 12 Hari')
                            ->maxLength(255),

                        Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '1 Bintang',
                                2 => '2 Bintang',
                                3 => '3 Bintang',
                                4 => '4 Bintang',
                                5 => '5 Bintang',
                            ])
                            ->default(5)
                            ->required()
                            ->native(false),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Foto Jamaah')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->directory('testimonials')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048),
                    ]),

                Section::make('Isi Testimoni')
                    ->schema([
                        Textarea::make('message')
                            ->label('Pesan Testimoni')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}