<?php

namespace App\Filament\Resources\RegistrationPayments\Schemas;

use App\Models\Registration;
use App\Models\RegistrationPayment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class RegistrationPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pendaftaran')
                    ->description('Pilih pendaftaran jamaah yang terkait dengan pembayaran ini.')
                    ->schema([
                        Select::make('registration_id')
                            ->label('Pendaftaran Jamaah')
                            ->default(fn () => request()->query('registration_id'))
                            ->options(function () {
                                return Registration::query()
                                    ->with('package')
                                    ->latest()
                                    ->get()
                                    ->mapWithKeys(function (Registration $registration) {
                                        $packageName = $registration->package?->title ?: 'Tanpa Paket';

                                        return [
                                            $registration->id => '#' . $registration->id
                                                . ' - '
                                                . $registration->name
                                                . ' - '
                                                . $packageName
                                                . ' - '
                                                . $registration->total_participants
                                                . ' peserta',
                                        ];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(),

                        Placeholder::make('registration_summary')
                            ->label('Ringkasan Tagihan')
                            ->content(function ($get) {
                                $registrationId = $get('registration_id');

                                if (! $registrationId) {
                                    return new HtmlString(
                                        '<div style="
                                            padding: 16px;
                                            border: 1px dashed #3f3f46;
                                            border-radius: 14px;
                                            color: #a1a1aa;
                                            background: rgba(255,255,255,0.03);
                                        ">
                                            Pilih pendaftaran terlebih dahulu.
                                        </div>'
                                    );
                                }

                                $registration = Registration::query()
                                    ->with(['package', 'payments'])
                                    ->find($registrationId);

                                if (! $registration) {
                                    return new HtmlString(
                                        '<div style="
                                            padding: 16px;
                                            border: 1px dashed #3f3f46;
                                            border-radius: 14px;
                                            color: #a1a1aa;
                                            background: rgba(255,255,255,0.03);
                                        ">
                                            Data pendaftaran tidak ditemukan.
                                        </div>'
                                    );
                                }

                                $format = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');

                                return new HtmlString('
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
                                            <div style="margin-top: 6px; font-size: 20px; font-weight: 800; color: #22c55e;">' . e($format($registration->total_bill)) . '</div>
                                        </div>

                                        <div style="
                                            padding: 16px;
                                            border-radius: 16px;
                                            background: rgba(59, 130, 246, 0.10);
                                            border: 1px solid rgba(59, 130, 246, 0.35);
                                        ">
                                            <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Total Bayar Valid</div>
                                            <div style="margin-top: 6px; font-size: 20px; font-weight: 800; color: #38bdf8;">' . e($format($registration->total_paid)) . '</div>
                                        </div>

                                        <div style="
                                            padding: 16px;
                                            border-radius: 16px;
                                            background: rgba(245, 158, 11, 0.10);
                                            border: 1px solid rgba(245, 158, 11, 0.35);
                                        ">
                                            <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Refund Valid</div>
                                            <div style="margin-top: 6px; font-size: 20px; font-weight: 800; color: #f59e0b;">' . e($format($registration->total_refund)) . '</div>
                                        </div>

                                        <div style="
                                            padding: 16px;
                                            border-radius: 16px;
                                            background: rgba(239, 68, 68, 0.10);
                                            border: 1px solid rgba(239, 68, 68, 0.35);
                                        ">
                                            <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">Sisa Tagihan</div>
                                            <div style="margin-top: 6px; font-size: 20px; font-weight: 800; color: #ef4444;">' . e($format($registration->remaining_payment)) . '</div>
                                        </div>
                                    </div>
                                ');
                            })
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Detail Pembayaran')
                    ->description('Input DP, cicilan, pelunasan, biaya tambahan, atau refund.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                TextInput::make('payment_code')
                                    ->label('Kode Pembayaran')
                                    ->placeholder('Contoh: PAY-0001')
                                    ->default(fn () => 'PAY-' . now()->format('YmdHis'))
                                    ->maxLength(100),

                                Select::make('payment_type')
                                    ->label('Jenis Pembayaran')
                                    ->options(RegistrationPayment::paymentTypeOptions())
                                    ->default('dp')
                                    ->required(),

                                TextInput::make('amount')
                                    ->label('Nominal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->minValue(0),

                                Select::make('payment_method')
                                    ->label('Metode Pembayaran')
                                    ->options(RegistrationPayment::paymentMethodOptions())
                                    ->default('transfer_bank')
                                    ->required(),

                                DatePicker::make('paid_at')
                                    ->label('Tanggal Bayar')
                                    ->native(false)
                                    ->displayFormat('d M Y'),

                                Select::make('status')
                                    ->label('Status Verifikasi')
                                    ->options(RegistrationPayment::statusOptions())
                                    ->default('menunggu_verifikasi')
                                    ->required(),

                                DatePicker::make('verified_at')
                                    ->label('Tanggal Verifikasi')
                                    ->native(false)
                                    ->displayFormat('d M Y'),

                                FileUpload::make('proof_file')
                                    ->label('Bukti Pembayaran')
                                    ->disk('public')
                                    ->directory('payment-proofs')
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
                                    ->columnSpanFull(),

                                Textarea::make('note')
                                    ->label('Catatan Pembayaran')
                                    ->rows(3)
                                    ->placeholder('Contoh: Transfer ke BSI, bukti kurang jelas, pembayaran sudah valid, dll.')
                                    ->columnSpanFull(),

                                Placeholder::make('proof_preview')
                                    ->label('Live Preview Bukti Pembayaran')
                                    ->content(function ($get) {
                                        return self::documentPreviewHtml($get('proof_file'));
                                    })
                                    ->columnSpanFull(),

                                Placeholder::make('receipt_link')
                                    ->label('Cetak Kwitansi')
                                    ->content(function ($record) {
                                        if (! $record || ! $record->id) {
                                            return new HtmlString(
                                                '<div style="
                                                    padding: 12px;
                                                    border: 1px dashed #3f3f46;
                                                    border-radius: 12px;
                                                    color: #a1a1aa;
                                                ">
                                                    Simpan data terlebih dahulu untuk mencetak kwitansi.
                                                </div>'
                                            );
                                        }

                                        $url = route('admin.payments.receipt-pdf', $record);

                                        return new HtmlString('
                                            <a 
                                                href="' . e($url) . '" 
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
                                                Cetak Kwitansi Pembayaran
                                            </a>
                                        ');
                                    })
                                    ->columnSpanFull(),
                            ]),
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
                    Belum ada file.
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
                        alt="Preview Bukti Pembayaran"
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
                    Buka / Download File
                </a>
            </div>
        ');
    }
}