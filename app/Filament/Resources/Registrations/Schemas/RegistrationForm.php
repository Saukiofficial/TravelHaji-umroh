<?php

namespace App\Filament\Resources\Registrations\Schemas;

use App\Models\Package;
use App\Models\RegistrationDocument;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Booking / Pendaftar Utama')
                    ->description('Data utama pendaftaran. Data jamaah per orang ada pada bagian Peserta Jamaah.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'xl' => 2,
                        ])
                            ->schema([
                                Select::make('package_id')
                                    ->label('Paket')
                                    ->options(
                                        Package::query()
                                            ->orderBy('title')
                                            ->pluck('title', 'id')
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('status')
                                    ->label('Status Pendaftaran')
                                    ->options([
                                        'baru' => 'Baru',
                                        'dihubungi' => 'Dihubungi',
                                        'proses' => 'Proses',
                                        'dokumen_lengkap' => 'Dokumen Lengkap',
                                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                        'selesai' => 'Selesai',
                                        'batal' => 'Batal',
                                    ])
                                    ->default('baru')
                                    ->required(),

                                TextInput::make('name')
                                    ->label('Nama Pendaftar Utama')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->label('Nomor WhatsApp Pendaftar')
                                    ->tel()
                                    ->required()
                                    ->maxLength(30),

                                TextInput::make('email')
                                    ->label('Email Pendaftar')
                                    ->email()
                                    ->maxLength(255),

                                TextInput::make('total_participants')
                                    ->label('Jumlah Peserta')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required()
                                    ->suffix('Orang'),

                                Textarea::make('address')
                                    ->label('Alamat Pendaftar')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Textarea::make('note')
                                    ->label('Catatan Pendaftaran')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Ringkasan Pembayaran')
                    ->description('Pembayaran dikelola dari menu Keuangan Jamaah > Pembayaran Jamaah. Bagian ini hanya menampilkan ringkasan pembayaran.')
                    ->schema([
                        Placeholder::make('payment_summary')
                            ->label('Status Pembayaran Jamaah')
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
                                            Ringkasan pembayaran akan muncul setelah data pendaftaran disimpan.
                                        </div>'
                                    );
                                }

                                $record->loadMissing(['package', 'payments']);

                                $format = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');

                                $totalBill = $format($record->total_bill);
                                $totalPaid = $format($record->total_paid);
                                $totalRefund = $format($record->total_refund);
                                $remainingPayment = $format($record->remaining_payment);

                                $paymentStatus = match ($record->payment_status) {
                                    'belum_ada_tagihan' => 'Belum Ada Tagihan',
                                    'belum_bayar' => 'Belum Bayar',
                                    'sebagian' => 'Bayar Sebagian',
                                    'lunas' => 'Lunas',
                                    default => '-',
                                };

                                $statusColor = match ($record->payment_status) {
                                    'lunas' => '#22c55e',
                                    'sebagian' => '#f59e0b',
                                    'belum_bayar' => '#ef4444',
                                    default => '#a1a1aa',
                                };

                                $createPaymentUrl = url('/admin/registration-payments/create?registration_id=' . $record->id);
                                $paymentListUrl = url('/admin/registration-payments');

                                return new HtmlString('
                                    <div style="display: grid; gap: 18px;">
                                        <div style="
                                            display: grid;
                                            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                                            gap: 14px;
                                        ">
                                            <div style="
                                                padding: 16px;
                                                border-radius: 16px;
                                                background: rgba(34, 197, 94, 0.10);
                                                border: 1px solid rgba(34, 197, 94, 0.35);
                                            ">
                                                <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Total Tagihan</div>
                                                <div style="margin-top: 6px; font-size: 20px; font-weight: 800; color: #22c55e;">' . e($totalBill) . '</div>
                                            </div>

                                            <div style="
                                                padding: 16px;
                                                border-radius: 16px;
                                                background: rgba(59, 130, 246, 0.10);
                                                border: 1px solid rgba(59, 130, 246, 0.35);
                                            ">
                                                <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Total Bayar Valid</div>
                                                <div style="margin-top: 6px; font-size: 20px; font-weight: 800; color: #38bdf8;">' . e($totalPaid) . '</div>
                                            </div>

                                            <div style="
                                                padding: 16px;
                                                border-radius: 16px;
                                                background: rgba(245, 158, 11, 0.10);
                                                border: 1px solid rgba(245, 158, 11, 0.35);
                                            ">
                                                <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Refund Valid</div>
                                                <div style="margin-top: 6px; font-size: 20px; font-weight: 800; color: #f59e0b;">' . e($totalRefund) . '</div>
                                            </div>

                                            <div style="
                                                padding: 16px;
                                                border-radius: 16px;
                                                background: rgba(239, 68, 68, 0.10);
                                                border: 1px solid rgba(239, 68, 68, 0.35);
                                            ">
                                                <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Sisa Tagihan</div>
                                                <div style="margin-top: 6px; font-size: 20px; font-weight: 800; color: #ef4444;">' . e($remainingPayment) . '</div>
                                            </div>
                                        </div>

                                        <div style="
                                            display: flex;
                                            flex-wrap: wrap;
                                            align-items: center;
                                            justify-content: space-between;
                                            gap: 12px;
                                            padding: 16px;
                                            border-radius: 16px;
                                            background: rgba(255,255,255,0.03);
                                            border: 1px solid #3f3f46;
                                        ">
                                            <div>
                                                <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Status Pembayaran</div>
                                                <div style="margin-top: 4px; font-size: 18px; font-weight: 900; color: ' . e($statusColor) . ';">
                                                    ' . e($paymentStatus) . '
                                                </div>
                                            </div>

                                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                <a 
                                                    href="' . e($createPaymentUrl) . '" 
                                                    target="_blank" 
                                                    style="
                                                        display: inline-flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        padding: 10px 16px;
                                                        border-radius: 12px;
                                                        background: #004f41;
                                                        color: #ffffff;
                                                        font-weight: 800;
                                                        text-decoration: none;
                                                    "
                                                >
                                                    Tambah Pembayaran
                                                </a>

                                                <a 
                                                    href="' . e($paymentListUrl) . '" 
                                                    target="_blank" 
                                                    style="
                                                        display: inline-flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        padding: 10px 16px;
                                                        border-radius: 12px;
                                                        background: #c68b27;
                                                        color: #ffffff;
                                                        font-weight: 800;
                                                        text-decoration: none;
                                                    "
                                                >
                                                    Lihat Riwayat Pembayaran
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                ');
                            })
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Peserta Jamaah')
                    ->description('Data jamaah per orang. Data ini otomatis masuk dari form pendaftaran frontend dan akan terbaca di Manifest Keberangkatan.')
                    ->schema([
                        Repeater::make('participants')
                            ->label('Daftar Peserta')
                            ->relationship('participants')
                            ->schema([
                                Section::make('Data Diri Peserta')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 2,
                                        ])
                                            ->schema([
                                                TextInput::make('order_number')
                                                    ->label('Urutan Peserta')
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->default(1)
                                                    ->required(),

                                                TextInput::make('name')
                                                    ->label('Nama Lengkap Peserta')
                                                    ->required()
                                                    ->maxLength(255),

                                                Select::make('gender')
                                                    ->label('Jenis Kelamin')
                                                    ->options([
                                                        'Laki-laki' => 'Laki-laki',
                                                        'Perempuan' => 'Perempuan',
                                                    ])
                                                    ->searchable(),

                                                TextInput::make('birth_place')
                                                    ->label('Tempat Lahir')
                                                    ->maxLength(255),

                                                DatePicker::make('birth_date')
                                                    ->label('Tanggal Lahir')
                                                    ->native(false)
                                                    ->displayFormat('d M Y'),

                                                TextInput::make('nik')
                                                    ->label('NIK')
                                                    ->maxLength(30),

                                                TextInput::make('phone')
                                                    ->label('Nomor WhatsApp Peserta')
                                                    ->tel()
                                                    ->maxLength(30),

                                                TextInput::make('email')
                                                    ->label('Email Peserta')
                                                    ->email()
                                                    ->maxLength(255),

                                                Textarea::make('address')
                                                    ->label('Alamat Peserta')
                                                    ->rows(2)
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->compact(),

                                Section::make('Data Paspor')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 3,
                                        ])
                                            ->schema([
                                                TextInput::make('passport_number')
                                                    ->label('Nomor Paspor')
                                                    ->maxLength(100),

                                                DatePicker::make('passport_issued_at')
                                                    ->label('Tanggal Terbit Paspor')
                                                    ->native(false)
                                                    ->displayFormat('d M Y'),

                                                DatePicker::make('passport_expired_at')
                                                    ->label('Tanggal Expired Paspor')
                                                    ->native(false)
                                                    ->displayFormat('d M Y'),
                                            ]),
                                    ])
                                    ->compact()
                                    ->collapsed(),

                                Section::make('Kontak Darurat & Catatan')
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 2,
                                        ])
                                            ->schema([
                                                TextInput::make('emergency_contact_name')
                                                    ->label('Nama Kontak Darurat')
                                                    ->maxLength(255),

                                                TextInput::make('emergency_contact_phone')
                                                    ->label('Nomor Kontak Darurat')
                                                    ->tel()
                                                    ->maxLength(30),

                                                Textarea::make('health_note')
                                                    ->label('Catatan Kesehatan')
                                                    ->rows(2)
                                                    ->placeholder('Contoh: riwayat penyakit, alergi, kebutuhan khusus, dll.')
                                                    ->columnSpanFull(),

                                                Textarea::make('note')
                                                    ->label('Catatan Peserta')
                                                    ->rows(2)
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->compact()
                                    ->collapsed(),

                                Section::make('Dokumen Peserta')
                                    ->description('Dokumen yang diupload jamaah dari frontend otomatis masuk ke bagian ini. Admin cukup verifikasi status dokumennya.')
                                    ->schema([
                                        Repeater::make('documents')
                                            ->label('Dokumen Peserta')
                                            ->relationship('documents')
                                            ->schema([
                                                Select::make('document_type')
                                                    ->label('Jenis Dokumen')
                                                    ->options(RegistrationDocument::documentTypeOptions())
                                                    ->searchable()
                                                    ->required()
                                                    ->columnSpan([
                                                        'default' => 1,
                                                        'md' => 2,
                                                    ]),

                                                Select::make('status')
                                                    ->label('Status Verifikasi')
                                                    ->options(RegistrationDocument::statusOptions())
                                                    ->default('belum_dicek')
                                                    ->required()
                                                    ->columnSpan([
                                                        'default' => 1,
                                                        'md' => 1,
                                                    ]),

                                                DatePicker::make('verified_at')
                                                    ->label('Tanggal Verifikasi')
                                                    ->native(false)
                                                    ->displayFormat('d M Y')
                                                    ->columnSpan([
                                                        'default' => 1,
                                                        'md' => 1,
                                                    ]),

                                                FileUpload::make('file_path')
                                                    ->label('File Dokumen')
                                                    ->disk('public')
                                                    ->directory('registration-documents')
                                                    ->visibility('public')
                                                    ->acceptedFileTypes([
                                                        'application/pdf',
                                                        'image/jpeg',
                                                        'image/png',
                                                        'image/jpg',
                                                        'image/webp',
                                                    ])
                                                    ->maxSize(4096)
                                                    ->openable()
                                                    ->downloadable()
                                                    ->previewable()
                                                    ->helperText('Format JPG, PNG, WEBP, atau PDF. Maksimal 4 MB.')
                                                    ->columnSpanFull(),

                                                Textarea::make('note')
                                                    ->label('Catatan Verifikasi')
                                                    ->rows(2)
                                                    ->placeholder('Contoh: KTP buram, paspor valid, dokumen perlu revisi, dll.')
                                                    ->columnSpanFull(),

                                                Placeholder::make('file_preview')
                                                    ->label('Live Preview Dokumen')
                                                    ->content(function ($get) {
                                                        return self::documentPreviewHtml($get('file_path'));
                                                    })
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns([
                                                'default' => 1,
                                                'md' => 4,
                                            ])
                                            ->itemLabel(function (array $state): ?string {
                                                $type = $state['document_type'] ?? null;

                                                if (! $type) {
                                                    return 'Dokumen Peserta';
                                                }

                                                return RegistrationDocument::documentTypeOptions()[$type] ?? 'Dokumen Peserta';
                                            })
                                            ->addActionLabel('Tambah Dokumen Peserta')
                                            ->reorderable(false)
                                            ->collapsible()
                                            ->collapsed()
                                            ->columnSpanFull(),
                                    ])
                                    ->compact(),
                            ])
                            ->columns(1)
                            ->itemLabel(function (array $state): ?string {
                                $order = $state['order_number'] ?? null;
                                $name = $state['name'] ?? null;

                                if ($order && $name) {
                                    return 'Peserta ' . $order . ' - ' . $name;
                                }

                                if ($name) {
                                    return $name;
                                }

                                return 'Peserta Jamaah';
                            })
                            ->addActionLabel('Tambah Peserta Jamaah')
                            ->reorderable(false)
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function documentPreviewHtml($filePath): HtmlString
    {
        if (! $filePath) {
            return new HtmlString(
                '<div style="
                    padding: 14px;
                    border: 1px dashed #3f3f46;
                    border-radius: 14px;
                    color: #a1a1aa;
                    background: rgba(255,255,255,0.03);
                ">
                    Belum ada file dokumen.
                </div>'
            );
        }

        if (is_array($filePath)) {
            $filePath = reset($filePath);
        }

        $url = Storage::disk('public')->url($filePath);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            return new HtmlString('
                <div style="
                    border: 1px solid #3f3f46;
                    border-radius: 16px;
                    overflow: hidden;
                    background: #111827;
                    width: 100%;
                    max-width: 900px;
                ">
                    <img 
                        src="' . e($url) . '" 
                        alt="Preview Dokumen"
                        style="
                            width: 100%;
                            height: auto;
                            display: block;
                        "
                    />
                </div>

                <div style="margin-top: 10px;">
                    <a 
                        href="' . e($url) . '" 
                        target="_blank" 
                        style="
                            color: #22c55e;
                            font-weight: 700;
                            text-decoration: underline;
                        "
                    >
                        Buka Gambar di Tab Baru
                    </a>
                </div>
            ');
        }

        if ($extension === 'pdf') {
            return new HtmlString('
                <div style="
                    border: 1px solid #3f3f46;
                    border-radius: 16px;
                    overflow: hidden;
                    background: #111827;
                    width: 100%;
                ">
                    <iframe 
                        src="' . e($url) . '#toolbar=1&navpanes=0&scrollbar=1" 
                        style="
                            width: 100%;
                            height: 620px;
                            border: 0;
                            background: #111827;
                        "
                    ></iframe>
                </div>

                <div style="
                    margin-top: 10px;
                    display: flex;
                    gap: 12px;
                    flex-wrap: wrap;
                ">
                    <a 
                        href="' . e($url) . '" 
                        target="_blank" 
                        style="
                            color: #22c55e;
                            font-weight: 700;
                            text-decoration: underline;
                        "
                    >
                        Buka PDF di Tab Baru
                    </a>

                    <a 
                        href="' . e($url) . '" 
                        download
                        style="
                            color: #38bdf8;
                            font-weight: 700;
                            text-decoration: underline;
                        "
                    >
                        Download PDF
                    </a>
                </div>
            ');
        }

        return new HtmlString('
            <div style="
                padding: 16px;
                border: 1px solid #3f3f46;
                border-radius: 14px;
                background: rgba(255,255,255,0.03);
            ">
                <a 
                    href="' . e($url) . '" 
                    target="_blank" 
                    style="
                        color: #22c55e;
                        font-weight: 700;
                        text-decoration: underline;
                    "
                >
                    Buka / Download Dokumen
                </a>
            </div>
        ');
    }
}