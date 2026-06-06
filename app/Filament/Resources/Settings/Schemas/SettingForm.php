<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Pengaturan Website')
                    ->tabs([
                        Tabs\Tab::make('Identitas & Tampilan')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Section::make('Identitas Website')
                                    ->description('Atur identitas utama website seperti nama brand, logo, dan gambar hero.')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 2,
                                        ])
                                            ->schema([
                                                TextInput::make('website_name')
                                                    ->label('Nama Website')
                                                    ->placeholder('Contoh: Ajmal Noor Wisata')
                                                    ->required()
                                                    ->maxLength(255),

                                                FileUpload::make('logo')
                                                    ->label('Logo Website')
                                                    ->image()
                                                    ->directory('settings')
                                                    ->disk('public')
                                                    ->visibility('public')
                                                    ->imageEditor()
                                                    ->maxSize(2048)
                                                    ->openable()
                                                    ->downloadable()
                                                    ->previewable()
                                                    ->helperText('Logo utama yang tampil di navbar dan beberapa bagian website. Disarankan PNG transparan.'),

                                                FileUpload::make('hero_image')
                                                    ->label('Gambar Hero Website')
                                                    ->image()
                                                    ->directory('settings/hero')
                                                    ->disk('public')
                                                    ->visibility('public')
                                                    ->imageEditor()
                                                    ->maxSize(4096)
                                                    ->openable()
                                                    ->downloadable()
                                                    ->previewable()
                                                    ->helperText('Gambar ini tampil di bagian kanan hero halaman utama. Disarankan gambar Ka’bah, jamaah, masjid, atau suasana ibadah dengan ukuran minimal 1200x900.')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Kontak Travel')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make('Informasi Kontak')
                                    ->description('Informasi kontak yang tampil di website, tombol WhatsApp, footer, dan halaman kontak.')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 2,
                                            'xl' => 3,
                                        ])
                                            ->schema([
                                                TextInput::make('phone')
                                                    ->label('Nomor Telepon')
                                                    ->tel()
                                                    ->placeholder('Contoh: 081234567890')
                                                    ->maxLength(255),

                                                TextInput::make('whatsapp')
                                                    ->label('Nomor WhatsApp')
                                                    ->tel()
                                                    ->placeholder('Contoh: 6281234567890')
                                                    ->helperText('Gunakan format 62, bukan 08. Contoh: 6281234567890.')
                                                    ->maxLength(255),

                                                TextInput::make('email')
                                                    ->label('Email')
                                                    ->email()
                                                    ->placeholder('admin@ajmalnoorwisata.com')
                                                    ->maxLength(255),

                                                Textarea::make('address')
                                                    ->label('Alamat Kantor')
                                                    ->rows(4)
                                                    ->placeholder('Masukkan alamat lengkap kantor travel.')
                                                    ->columnSpanFull(),

                                                Textarea::make('google_maps')
                                                    ->label('Embed Google Maps')
                                                    ->rows(5)
                                                    ->placeholder('<iframe src="..."></iframe>')
                                                    ->helperText('Tempel kode iframe Google Maps di sini.')
                                                    ->columnSpanFull(),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('Header Dokumen / Laporan')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Header Laporan & Cetak PDF')
                                    ->description('Pengaturan ini dipakai untuk header cetak berkas pendaftaran, kwitansi pembayaran, manifest, dan export laporan.')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 2,
                                        ])
                                            ->schema([
                                                TextInput::make('report_brand_name')
                                                    ->label('Nama Brand Laporan')
                                                    ->placeholder('Ajmal Noor Wisata')
                                                    ->helperText('Jika dikosongkan, sistem memakai Nama Website.')
                                                    ->maxLength(255),

                                                TextInput::make('report_brand_tagline')
                                                    ->label('Tagline Laporan')
                                                    ->placeholder('Travel Haji & Umroh')
                                                    ->helperText('Contoh: Travel Haji & Umroh Terpercaya.')
                                                    ->maxLength(255),

                                                FileUpload::make('report_logo')
                                                    ->label('Logo Laporan')
                                                    ->image()
                                                    ->directory('settings/report')
                                                    ->disk('public')
                                                    ->visibility('public')
                                                    ->imageEditor()
                                                    ->openable()
                                                    ->downloadable()
                                                    ->previewable()
                                                    ->helperText('Jika dikosongkan, sistem memakai Logo Website.')
                                                    ->maxSize(2048)
                                                    ->columnSpanFull(),

                                                TextInput::make('report_phone')
                                                    ->label('Nomor Kontak Laporan')
                                                    ->tel()
                                                    ->placeholder('Contoh: 6281234567890')
                                                    ->helperText('Jika dikosongkan, sistem memakai WhatsApp/Telepon utama.')
                                                    ->maxLength(255),

                                                TextInput::make('report_email')
                                                    ->label('Email Laporan')
                                                    ->email()
                                                    ->placeholder('admin@ajmalnoorwisata.com')
                                                    ->helperText('Jika dikosongkan, sistem memakai Email utama.')
                                                    ->maxLength(255),

                                                TextInput::make('report_header_color')
                                                    ->label('Warna Header Laporan')
                                                    ->placeholder('004F41')
                                                    ->default('004F41')
                                                    ->maxLength(6)
                                                    ->helperText('Isi kode HEX tanpa tanda #. Contoh hijau: 004F41, biru: 0D47A1.'),

                                                TextInput::make('report_accent_color')
                                                    ->label('Warna Aksen Laporan')
                                                    ->placeholder('E8BD62')
                                                    ->default('E8BD62')
                                                    ->maxLength(6)
                                                    ->helperText('Isi kode HEX tanpa tanda #. Contoh gold: E8BD62.'),

                                                Textarea::make('report_address')
                                                    ->label('Alamat Laporan')
                                                    ->rows(4)
                                                    ->placeholder('Alamat kantor travel yang akan tampil di laporan/cetak PDF.')
                                                    ->helperText('Jika dikosongkan, sistem memakai Alamat Kantor utama.')
                                                    ->columnSpanFull(),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('Media Sosial')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Link Media Sosial')
                                    ->description('Atur link media sosial yang akan tampil di website.')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 2,
                                        ])
                                            ->schema([
                                                TextInput::make('instagram')
                                                    ->label('Instagram')
                                                    ->url()
                                                    ->placeholder('https://instagram.com/...')
                                                    ->maxLength(255),

                                                TextInput::make('facebook')
                                                    ->label('Facebook')
                                                    ->url()
                                                    ->placeholder('https://facebook.com/...')
                                                    ->maxLength(255),

                                                TextInput::make('tiktok')
                                                    ->label('TikTok')
                                                    ->url()
                                                    ->placeholder('https://tiktok.com/@...')
                                                    ->maxLength(255),

                                                TextInput::make('youtube')
                                                    ->label('YouTube')
                                                    ->url()
                                                    ->placeholder('https://youtube.com/...')
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('SEO Website')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Section::make('Pengaturan SEO')
                                    ->description('Atur meta title dan meta description untuk kebutuhan SEO mesin pencari.')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->placeholder('Contoh: Ajmal Noor Wisata - Travel Haji & Umroh Terpercaya')
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->placeholder('Tulis deskripsi singkat website untuk mesin pencari.')
                                            ->rows(5)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ]);
    }
}