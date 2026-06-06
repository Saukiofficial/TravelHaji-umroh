<?php

namespace App\Filament\Resources\WhatsAppBroadcasts\Schemas;

use App\Models\RegistrationParticipant;
use App\Models\WhatsAppBroadcast;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class WhatsAppBroadcastForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Broadcast')
                    ->description('Buat pesan broadcast untuk jamaah yang dipilih.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Broadcast')
                                    ->placeholder('Contoh: Pengumuman Jadwal Manasik')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('status')
                                    ->label('Status')
                                    ->options(WhatsAppBroadcast::statusOptions())
                                    ->default('draft')
                                    ->required()
                                    ->native(false),
                            ]),

                        Textarea::make('message')
                            ->label('Isi Pesan Broadcast')
                            ->placeholder("Contoh:\nAssalamu’alaikum Bapak/Ibu {nama},\n\nKami informasikan bahwa jadwal manasik akan dilaksanakan pada ...")
                            ->helperText('Gunakan {nama}, {paket}, {link_revisi}, dan {whatsapp} untuk data otomatis per jamaah.')
                            ->rows(8)
                            ->required()
                            ->columnSpanFull(),

                        Select::make('target_participant_ids')
                            ->label('Pilih Jamaah Penerima')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => self::participantOptions())
                            ->helperText('Pilih satu atau beberapa jamaah yang akan menerima broadcast. Nomor WhatsApp diambil dari data peserta, jika kosong akan memakai nomor pendaftar utama.')
                            ->columnSpanFull(),

                        Placeholder::make('template_info')
                            ->label('Variabel Pesan')
                            ->content(new HtmlString('
                                <div style="
                                    padding: 16px;
                                    border-radius: 16px;
                                    border: 1px solid rgba(148, 163, 184, 0.25);
                                    background: rgba(255,255,255,0.03);
                                    color: #a1a1aa;
                                    line-height: 1.8;
                                ">
                                    <div style="font-weight: 900; color: #ffffff; margin-bottom: 8px;">Variabel yang bisa digunakan:</div>
                                    <div><code>{nama}</code> = Nama jamaah</div>
                                    <div><code>{paket}</code> = Nama paket yang dipilih</div>
                                    <div><code>{link_revisi}</code> = Link revisi dokumen jamaah</div>
                                    <div><code>{whatsapp}</code> = Nomor WhatsApp jamaah</div>
                                </div>
                            '))
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    private static function participantOptions(): array
    {
        return RegistrationParticipant::query()
            ->with(['registration.package'])
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function (RegistrationParticipant $participant) {
                $packageTitle = $participant->registration?->package?->title ?: 'Tanpa Paket';
                $phone = $participant->phone ?: $participant->registration?->phone ?: '-';

                $label = ($participant->name ?: 'Peserta Jamaah')
                    . ' — '
                    . $packageTitle
                    . ' — WA: '
                    . $phone;

                return [
                    $participant->id => $label,
                ];
            })
            ->toArray();
    }
}