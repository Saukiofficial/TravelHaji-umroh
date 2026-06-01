<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama Paket')
                    ->description('Atur jenis paket, nama paket, harga, jadwal, seat, dan status paket.')
                    ->schema([
                        Select::make('type')
                            ->label('Jenis Paket')
                            ->options([
                                'umroh' => 'Umroh',
                                'haji' => 'Haji',
                            ])
                            ->default('umroh')
                            ->required()
                            ->native(false),

                        TextInput::make('title')
                            ->label('Nama Paket')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(table: 'packages', column: 'slug', ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('price')
                            ->label('Harga Paket')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('Contoh: 29500000')
                            ->helperText('Isi angka saja. Contoh: 29500000.'),

                        TextInput::make('duration_days')
                            ->label('Durasi Perjalanan')
                            ->numeric()
                            ->suffix('Hari')
                            ->placeholder('Contoh: 12'),

                        DatePicker::make('departure_date')
                            ->label('Tanggal Keberangkatan')
                            ->native(false),

                        TextInput::make('seat')
                            ->label('Jumlah Seat')
                            ->numeric()
                            ->suffix('Jamaah')
                            ->placeholder('Contoh: 45'),

                        Toggle::make('is_featured')
                            ->label('Tampilkan sebagai Paket Unggulan')
                            ->default(false),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('published')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Transportasi dan Hotel')
                    ->description('Informasi maskapai dan hotel yang digunakan.')
                    ->schema([
                        TextInput::make('airline')
                            ->label('Maskapai')
                            ->maxLength(255)
                            ->placeholder('Contoh: Garuda Indonesia'),

                        TextInput::make('makkah_hotel')
                            ->label('Hotel Makkah')
                            ->maxLength(255)
                            ->placeholder('Contoh: Hotel Bintang 4 Makkah'),

                        TextInput::make('madinah_hotel')
                            ->label('Hotel Madinah')
                            ->maxLength(255)
                            ->placeholder('Contoh: Hotel Bintang 4 Madinah'),
                    ])
                    ->columns(3),

                Section::make('Gambar Paket')
                    ->description('Upload gambar utama paket. Gambar ini akan tampil di website.')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar Paket')
                            ->image()
                            ->directory('packages')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048),
                    ]),

                Section::make('Detail Paket')
                    ->description('Isi penjelasan lengkap paket, fasilitas, itinerary, dan syarat pendaftaran.')
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi Paket')
                            ->rows(5)
                            ->columnSpanFull(),

                        Textarea::make('facilities')
                            ->label('Fasilitas')
                            ->rows(7)
                            ->helperText('Tulis satu fasilitas per baris.')
                            ->columnSpanFull(),

                        Textarea::make('itinerary')
                            ->label('Itinerary')
                            ->rows(8)
                            ->helperText('Tulis jadwal perjalanan harian.')
                            ->columnSpanFull(),

                        Textarea::make('requirements')
                            ->label('Syarat Pendaftaran')
                            ->rows(6)
                            ->helperText('Tulis dokumen atau persyaratan pendaftaran.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}