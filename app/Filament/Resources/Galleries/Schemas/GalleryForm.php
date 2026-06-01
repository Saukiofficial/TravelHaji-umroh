<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Galeri')
                    ->description('Kelola dokumentasi foto atau video perjalanan jamaah.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Galeri')
                            ->required()
                            ->maxLength(255),

                        Select::make('type')
                            ->label('Jenis Galeri')
                            ->options([
                                'foto' => 'Foto',
                                'video' => 'Video',
                            ])
                            ->default('foto')
                            ->required()
                            ->native(false),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(3),

                Section::make('Media Galeri')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Foto Galeri')
                            ->image()
                            ->directory('galleries')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Upload gambar jika jenis galeri adalah foto.'),

                        TextInput::make('video_url')
                            ->label('URL Video')
                            ->url()
                            ->placeholder('Contoh: https://youtube.com/...')
                            ->helperText('Isi URL video jika jenis galeri adalah video.'),
                    ])
                    ->columns(2),

                Section::make('Deskripsi')
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi Galeri')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}