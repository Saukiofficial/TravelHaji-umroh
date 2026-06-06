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
                            ->native(false)
                            ->live()
                            ->helperText('Pilih Foto jika ingin upload gambar. Pilih Video jika ingin memakai link YouTube.'),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(3),

                Section::make('Media Galeri')
                    ->description('Untuk jenis Foto, upload gambar. Untuk jenis Video, isi link YouTube.')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Foto / Thumbnail Galeri')
                            ->image()
                            ->directory('galleries')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(4096)
                            ->helperText('Untuk galeri foto, gambar ini wajib. Untuk video, gambar ini bisa dipakai sebagai thumbnail opsional.')
                            ->required(fn ($get): bool => $get('type') === 'foto'),

                        TextInput::make('video_url')
                            ->label('URL Video YouTube')
                            ->url()
                            ->placeholder('Contoh: https://www.youtube.com/watch?v=xxxx atau https://youtu.be/xxxx')
                            ->helperText('Isi URL video jika jenis galeri adalah video. Mendukung link YouTube biasa, youtu.be, embed, live, dan shorts.')
                            ->required(fn ($get): bool => $get('type') === 'video')
                            ->visible(fn ($get): bool => $get('type') === 'video'),
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