<?php

namespace App\Filament\Resources\DepartureGroups\Schemas;

use App\Models\DepartureGroup;
use App\Models\DepartureGroupParticipant;
use App\Models\Package;
use App\Models\RegistrationParticipant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class DepartureGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Grup Keberangkatan')
                    ->description('Data utama grup keberangkatan, paket, jadwal, kuota, dan status operasional.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'xl' => 2,
                        ])
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Grup Keberangkatan')
                                    ->placeholder('Contoh: Umroh Reguler 31 Juli 2026')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('code')
                                    ->label('Kode Grup')
                                    ->placeholder('Contoh: UMR-310726')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(100),

                                Select::make('package_id')
                                    ->label('Paket Terkait')
                                    ->options(
                                        Package::query()
                                            ->orderBy('title')
                                            ->pluck('title', 'id')
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->preload(),

                                Select::make('type')
                                    ->label('Jenis Keberangkatan')
                                    ->options(DepartureGroup::typeOptions())
                                    ->default('umroh')
                                    ->required(),

                                Select::make('status')
                                    ->label('Status Grup')
                                    ->options(DepartureGroup::statusOptions())
                                    ->default('draft')
                                    ->required(),

                                TextInput::make('seat_quota')
                                    ->label('Kuota Seat')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->suffix('Seat')
                                    ->required(),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Jadwal dan Penerbangan')
                    ->description('Atur jadwal berangkat, pulang, bandara, maskapai, dan nomor penerbangan.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                DatePicker::make('departure_date')
                                    ->label('Tanggal Berangkat')
                                    ->native(false)
                                    ->displayFormat('d M Y'),

                                DatePicker::make('return_date')
                                    ->label('Tanggal Pulang')
                                    ->native(false)
                                    ->displayFormat('d M Y'),

                                DateTimePicker::make('departure_time')
                                    ->label('Waktu Berangkat')
                                    ->native(false)
                                    ->displayFormat('d M Y H:i'),

                                DateTimePicker::make('return_time')
                                    ->label('Waktu Pulang')
                                    ->native(false)
                                    ->displayFormat('d M Y H:i'),

                                TextInput::make('departure_airport')
                                    ->label('Bandara Keberangkatan')
                                    ->placeholder('Contoh: Juanda Surabaya')
                                    ->maxLength(255),

                                TextInput::make('arrival_airport')
                                    ->label('Bandara Tujuan')
                                    ->placeholder('Contoh: Jeddah / Madinah')
                                    ->maxLength(255),

                                TextInput::make('airline')
                                    ->label('Maskapai')
                                    ->placeholder('Contoh: Garuda Indonesia')
                                    ->maxLength(255),

                                TextInput::make('departure_flight_number')
                                    ->label('Nomor Penerbangan Berangkat')
                                    ->placeholder('Contoh: GA-123')
                                    ->maxLength(255),

                                TextInput::make('return_flight_number')
                                    ->label('Nomor Penerbangan Pulang')
                                    ->placeholder('Contoh: GA-456')
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Akomodasi dan Pembimbing')
                    ->description('Data hotel, tour leader, muthawif, dan titik kumpul jamaah.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                TextInput::make('makkah_hotel')
                                    ->label('Hotel Makkah')
                                    ->maxLength(255),

                                TextInput::make('madinah_hotel')
                                    ->label('Hotel Madinah')
                                    ->maxLength(255),

                                TextInput::make('tour_leader_name')
                                    ->label('Nama Tour Leader')
                                    ->maxLength(255),

                                TextInput::make('tour_leader_phone')
                                    ->label('Nomor Tour Leader')
                                    ->tel()
                                    ->maxLength(30),

                                TextInput::make('muthawif_name')
                                    ->label('Nama Muthawif')
                                    ->maxLength(255),

                                TextInput::make('muthawif_phone')
                                    ->label('Nomor Muthawif')
                                    ->tel()
                                    ->maxLength(30),

                                Textarea::make('meeting_point')
                                    ->label('Meeting Point')
                                    ->rows(3)
                                    ->placeholder('Contoh: Kantor Ajmal Noor Wisata / Bandara Juanda Terminal Internasional')
                                    ->columnSpanFull(),

                                Textarea::make('notes')
                                    ->label('Catatan Grup')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Ringkasan Seat')
                    ->schema([
                        Placeholder::make('seat_summary')
                            ->label('Ringkasan Kuota')
                            ->content(function ($record) {
                                if (! $record) {
                                    return new HtmlString(
                                        '<div style="
                                            padding: 16px;
                                            border: 1px dashed #3f3f46;
                                            border-radius: 14px;
                                            color: #a1a1aa;
                                            background: rgba(255,255,255,0.03);
                                        ">
                                            Ringkasan seat akan muncul setelah grup disimpan.
                                        </div>'
                                    );
                                }

                                $record->loadMissing('participants');

                                $quota = (int) $record->seat_quota;
                                $used = (int) $record->used_seats;
                                $remaining = (int) $record->remaining_seats;

                                return new HtmlString('
                                    <div style="
                                        display: grid;
                                        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                                        gap: 14px;
                                    ">
                                        <div style="
                                            padding: 16px;
                                            border-radius: 16px;
                                            background: rgba(59, 130, 246, 0.10);
                                            border: 1px solid rgba(59, 130, 246, 0.35);
                                        ">
                                            <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Kuota Seat</div>
                                            <div style="margin-top: 6px; font-size: 22px; font-weight: 800; color: #38bdf8;">' . e($quota) . ' Seat</div>
                                        </div>

                                        <div style="
                                            padding: 16px;
                                            border-radius: 16px;
                                            background: rgba(34, 197, 94, 0.10);
                                            border: 1px solid rgba(34, 197, 94, 0.35);
                                        ">
                                            <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Terisi</div>
                                            <div style="margin-top: 6px; font-size: 22px; font-weight: 800; color: #22c55e;">' . e($used) . ' Jamaah</div>
                                        </div>

                                        <div style="
                                            padding: 16px;
                                            border-radius: 16px;
                                            background: rgba(245, 158, 11, 0.10);
                                            border: 1px solid rgba(245, 158, 11, 0.35);
                                        ">
                                            <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Sisa Seat</div>
                                            <div style="margin-top: 6px; font-size: 22px; font-weight: 800; color: #f59e0b;">' . e($remaining) . ' Seat</div>
                                        </div>
                                    </div>
                                ');
                            })
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Peserta Manifest')
                    ->description('Masukkan peserta jamaah yang ikut dalam grup keberangkatan ini.')
                    ->schema([
                        Repeater::make('participants')
                            ->label('Daftar Peserta Keberangkatan')
                            ->relationship('participants')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                ])
                                    ->schema([
                                        Select::make('registration_participant_id')
                                            ->label('Peserta Jamaah')
                                            ->options(function () {
                                                return RegistrationParticipant::query()
                                                    ->with(['registration.package'])
                                                    ->orderBy('name')
                                                    ->get()
                                                    ->mapWithKeys(function (RegistrationParticipant $participant) {
                                                        $packageName = $participant->registration?->package?->title ?: 'Tanpa Paket';
                                                        $registrationName = $participant->registration?->name ?: 'Pendaftar';

                                                        return [
                                                            $participant->id => $participant->name
                                                                . ' - '
                                                                . $registrationName
                                                                . ' - '
                                                                . $packageName,
                                                        ];
                                                    })
                                                    ->toArray();
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function ($state, $set) {
                                                if (! $state) {
                                                    $set('registration_id', null);
                                                    return;
                                                }

                                                $participant = RegistrationParticipant::query()->find($state);

                                                $set('registration_id', $participant?->registration_id);
                                            })
                                            ->columnSpanFull(),

                                        Select::make('registration_id')
                                            ->label('Pendaftaran')
                                            ->relationship('registration', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),

                                        TextInput::make('manifest_number')
                                            ->label('Nomor Manifest')
                                            ->placeholder('Contoh: M-001')
                                            ->maxLength(100),

                                        TextInput::make('baggage_number')
                                            ->label('Nomor Koper')
                                            ->placeholder('Contoh: KPR-001')
                                            ->maxLength(100),

                                        TextInput::make('bus_number')
                                            ->label('Nomor Bus')
                                            ->placeholder('Contoh: BUS-01')
                                            ->maxLength(100),

                                        TextInput::make('room_number')
                                            ->label('Nomor Kamar')
                                            ->placeholder('Contoh: 1205')
                                            ->maxLength(100),

                                        Select::make('room_type')
                                            ->label('Tipe Kamar')
                                            ->options(DepartureGroupParticipant::roomTypeOptions())
                                            ->searchable(),

                                        Select::make('departure_status')
                                            ->label('Status Keberangkatan')
                                            ->options(DepartureGroupParticipant::departureStatusOptions())
                                            ->default('terdaftar')
                                            ->required(),

                                        Select::make('visa_status')
                                            ->label('Status Visa')
                                            ->options(DepartureGroupParticipant::visaStatusOptions())
                                            ->default('belum_diajukan')
                                            ->required(),

                                        TextInput::make('visa_number')
                                            ->label('Nomor Visa')
                                            ->maxLength(255),

                                        DatePicker::make('visa_issued_at')
                                            ->label('Tanggal Terbit Visa')
                                            ->native(false)
                                            ->displayFormat('d M Y'),

                                        Select::make('ticket_status')
                                            ->label('Status Tiket')
                                            ->options(DepartureGroupParticipant::ticketStatusOptions())
                                            ->default('belum_dipesan')
                                            ->required(),

                                        TextInput::make('ticket_number')
                                            ->label('Nomor Tiket')
                                            ->maxLength(255),

                                        TextInput::make('booking_code')
                                            ->label('Kode Booking')
                                            ->maxLength(255),

                                        Textarea::make('notes')
                                            ->label('Catatan Peserta')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columns(1)
                            ->itemLabel(function (array $state): ?string {
                                $participantId = $state['registration_participant_id'] ?? null;

                                if ($participantId) {
                                    $participant = RegistrationParticipant::query()->find($participantId);

                                    if ($participant) {
                                        return ($state['manifest_number'] ?? 'Manifest')
                                            . ' - '
                                            . $participant->name;
                                    }
                                }

                                return 'Peserta Keberangkatan';
                            })
                            ->addActionLabel('Tambah Peserta Manifest')
                            ->reorderable(false)
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}