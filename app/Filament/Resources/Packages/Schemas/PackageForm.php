<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Form Paket Umroh & Haji')
                    ->tabs([
                        Tabs\Tab::make('Informasi Paket')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Section::make('Informasi Utama Paket')
                                    ->description('Atur jenis paket, nama paket, slug URL, status publish, dan paket unggulan.')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 2,
                                        ])
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

                                                Select::make('status')
                                                    ->label('Status')
                                                    ->options([
                                                        'draft' => 'Draft',
                                                        'published' => 'Published',
                                                    ])
                                                    ->default('published')
                                                    ->required()
                                                    ->native(false),

                                                TextInput::make('title')
                                                    ->label('Nama Paket')
                                                    ->placeholder('Contoh: Paket Umroh Reguler 12 Hari')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                                        if ($operation === 'create') {
                                                            $set('slug', Str::slug($state));
                                                        }
                                                    })
                                                    ->columnSpanFull(),

                                                TextInput::make('slug')
                                                    ->label('Slug URL')
                                                    ->placeholder('contoh: paket-umroh-reguler-12-hari')
                                                    ->required()
                                                    ->unique(table: 'packages', column: 'slug', ignoreRecord: true)
                                                    ->maxLength(255)
                                                    ->columnSpanFull(),

                                                Toggle::make('is_featured')
                                                    ->label('Tampilkan sebagai Paket Unggulan')
                                                    ->helperText('Jika aktif, paket akan tampil pada bagian paket unggulan di halaman utama.')
                                                    ->default(false)
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Harga & Kuota')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Section::make('Harga, Durasi, dan Seat')
                                    ->description('Atur harga paket, durasi perjalanan, tanggal keberangkatan, dan jumlah seat.')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 2,
                                            'xl' => 4,
                                        ])
                                            ->schema([
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
                                                    ->native(false)
                                                    ->displayFormat('d M Y'),

                                                TextInput::make('seat')
                                                    ->label('Jumlah Seat')
                                                    ->numeric()
                                                    ->suffix('Jamaah')
                                                    ->placeholder('Contoh: 45'),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Transportasi & Hotel')
                            ->icon('heroicon-o-building-office-2')
                            ->schema([
                                Section::make('Transportasi dan Hotel')
                                    ->description('Informasi maskapai dan hotel yang digunakan dalam paket.')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 3,
                                        ])
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
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Gambar Paket')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make('Media Paket')
                                    ->description('Upload gambar utama paket. Gambar ini akan tampil di website, list paket, dan detail paket.')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Gambar Paket')
                                            ->image()
                                            ->directory('packages')
                                            ->disk('public')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->maxSize(2048)
                                            ->openable()
                                            ->downloadable()
                                            ->previewable()
                                            ->helperText('Disarankan gambar landscape ukuran 1200x800 atau 1600x1000. Format JPG, PNG, atau WEBP.')
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Deskripsi Paket')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Deskripsi dan Penjelasan Paket')
                                    ->description('Isi penjelasan utama paket yang akan tampil di halaman detail paket.')
                                    ->schema([
                                        Textarea::make('description')
                                            ->label('Deskripsi Paket')
                                            ->rows(8)
                                            ->placeholder('Tuliskan deskripsi singkat dan menarik tentang paket ini.')
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Fasilitas')
                            ->icon('heroicon-o-check-badge')
                            ->schema([
                                Section::make('Fasilitas Paket')
                                    ->description('Tuliskan fasilitas yang termasuk dalam paket.')
                                    ->schema([
                                        Textarea::make('facilities')
                                            ->label('Fasilitas')
                                            ->rows(12)
                                            ->helperText('Tulis satu fasilitas per baris agar lebih rapi saat ditampilkan.')
                                            ->placeholder("Contoh:\nTiket pesawat PP\nHotel Makkah dan Madinah\nVisa Umroh\nMakan 3x sehari\nPembimbing ibadah")
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Itinerary')
                            ->icon('heroicon-o-map')
                            ->schema([
                                Section::make('Itinerary Perjalanan')
                                    ->description('Tuliskan jadwal perjalanan harian paket.')
                                    ->schema([
                                        Textarea::make('itinerary')
                                            ->label('Itinerary')
                                            ->rows(14)
                                            ->helperText('Tulis jadwal perjalanan harian. Contoh: Hari 1, Hari 2, dan seterusnya.')
                                            ->placeholder("Contoh:\nHari 1: Berkumpul di bandara dan keberangkatan menuju Jeddah.\nHari 2: Tiba di Jeddah, perjalanan menuju Makkah.\nHari 3: Pelaksanaan ibadah umroh.")
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Syarat Pendaftaran')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                                Section::make('Syarat dan Ketentuan Pendaftaran')
                                    ->description('Tuliskan dokumen atau persyaratan yang harus disiapkan jamaah.')
                                    ->schema([
                                        Textarea::make('requirements')
                                            ->label('Syarat Pendaftaran')
                                            ->rows(12)
                                            ->helperText('Tulis dokumen atau persyaratan pendaftaran. Contoh: KTP, KK, Paspor, Pas Foto, dan lain-lain.')
                                            ->placeholder("Contoh:\nKTP\nKartu Keluarga\nPaspor aktif\nPas Foto 4x6\nBuku nikah/akta/ijazah jika diperlukan")
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ]);
    }
}