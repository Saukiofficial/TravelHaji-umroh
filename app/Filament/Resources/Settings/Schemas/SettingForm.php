<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Website')
                    ->description('Atur nama website, logo, dan informasi utama travel.')
                    ->schema([
                        TextInput::make('website_name')
                            ->label('Nama Website')
                            ->required()
                            ->maxLength(255),

                        FileUpload::make('logo')
                            ->label('Logo Website')
                            ->image()
                            ->directory('settings')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048),
                    ])
                    ->columns(2),

                Section::make('Kontak Travel')
                    ->description('Informasi kontak yang tampil di website.')
                    ->schema([
                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(255),

                        TextInput::make('whatsapp')
                            ->label('Nomor WhatsApp')
                            ->placeholder('Contoh: 6281234567890')
                            ->helperText('Gunakan format 62, bukan 08. Contoh: 6281234567890.')
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Textarea::make('address')
                            ->label('Alamat Kantor')
                            ->rows(3)
                            ->columnSpanFull(),

                        Textarea::make('google_maps')
                            ->label('Embed Google Maps')
                            ->rows(4)
                            ->helperText('Tempel kode iframe Google Maps di sini.')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Header Laporan & Cetak PDF')
                    ->description('Pengaturan ini dipakai untuk header cetak berkas pendaftaran, kwitansi pembayaran, manifest, dan export laporan.')
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
                            ->helperText('Jika dikosongkan, sistem memakai Logo Website.')
                            ->maxSize(2048),

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
                            ->helperText('Isi kode HEX tanpa tanda #. Contoh hijau: 004F41, biru: 0D47A1.')
                            ->default('004F41')
                            ->maxLength(6),

                        TextInput::make('report_accent_color')
                            ->label('Warna Aksen Laporan')
                            ->placeholder('E8BD62')
                            ->helperText('Isi kode HEX tanpa tanda #. Contoh gold: E8BD62.')
                            ->default('E8BD62')
                            ->maxLength(6),

                        Textarea::make('report_address')
                            ->label('Alamat Laporan')
                            ->rows(3)
                            ->placeholder('Alamat kantor travel yang akan tampil di laporan/cetak PDF.')
                            ->helperText('Jika dikosongkan, sistem memakai Alamat Kantor utama.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Media Sosial')
                    ->schema([
                        TextInput::make('instagram')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('facebook')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('tiktok')
                            ->label('TikTok')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('youtube')
                            ->label('YouTube')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('SEO Website')
                    ->description('Atur meta title dan meta description untuk kebutuhan SEO.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}