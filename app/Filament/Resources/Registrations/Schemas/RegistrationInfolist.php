<?php

namespace App\Filament\Resources\Registrations\Schemas;

use App\Models\Registration;
use App\Models\RegistrationDocument;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class RegistrationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Booking / Pendaftar Utama')
                    ->schema([
                        TextEntry::make('package.title')
                            ->label('Paket')
                            ->placeholder('-'),

                        TextEntry::make('name')
                            ->label('Nama Pendaftar')
                            ->placeholder('-'),

                        TextEntry::make('phone')
                            ->label('Nomor WhatsApp')
                            ->placeholder('-'),

                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('-'),

                        TextEntry::make('total_participants')
                            ->label('Jumlah Peserta')
                            ->numeric()
                            ->suffix(' Orang'),

                        TextEntry::make('status')
                            ->label('Status Pendaftaran')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => self::statusLabel($state))
                            ->color(fn (?string $state): string => self::statusColor($state)),

                        TextEntry::make('address')
                            ->label('Alamat')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('note')
                            ->label('Catatan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 3,
                    ]),

                Section::make('Ringkasan Peserta Jamaah')
                    ->description('Data peserta yang masuk dari frontend dan menjadi sumber data manifest.')
                    ->schema([
                        TextEntry::make('participants_summary')
                            ->label('')
                            ->state(fn (Registration $record): HtmlString => self::participantsSummaryHtml($record))
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Konfirmasi Revisi Dokumen via WhatsApp')
                    ->description('Gunakan bagian ini untuk mengirim link revisi dokumen ke jamaah yang dokumennya Perlu Revisi atau Ditolak.')
                    ->schema([
                        TextEntry::make('document_revision_whatsapp')
                            ->label('')
                            ->state(fn (Registration $record): HtmlString => self::revisionWhatsappHtml($record))
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Status Dokumen Peserta')
                    ->description('Ringkasan status dokumen seluruh peserta dalam pendaftaran ini.')
                    ->schema([
                        TextEntry::make('documents_summary')
                            ->label('')
                            ->state(fn (Registration $record): HtmlString => self::documentsSummaryHtml($record))
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Informasi Sistem')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Tanggal Daftar')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),
            ]);
    }

    private static function participantsSummaryHtml(Registration $record): HtmlString
    {
        $record->loadMissing(['package', 'participants.documents']);

        if ($record->participants->isEmpty()) {
            return new HtmlString(self::emptyBox('Belum ada peserta jamaah pada pendaftaran ini.'));
        }

        $html = '<div style="display: grid; gap: 14px;">';

        foreach ($record->participants as $participant) {
            $phone = $participant->phone ?: $record->phone ?: '-';
            $email = $participant->email ?: $record->email ?: '-';
            $package = $record->package?->title ?: '-';

            $html .= '
                <div style="
                    padding: 18px;
                    border-radius: 18px;
                    border: 1px solid rgba(148, 163, 184, 0.25);
                    background: rgba(255,255,255,0.03);
                ">
                    <div style="
                        display: flex;
                        justify-content: space-between;
                        gap: 12px;
                        flex-wrap: wrap;
                        align-items: flex-start;
                    ">
                        <div>
                            <div style="font-size: 12px; font-weight: 800; color: #E8BD62; text-transform: uppercase; letter-spacing: .08em;">
                                Peserta ' . e((string) ($participant->order_number ?: '-')) . '
                            </div>
                            <div style="margin-top: 6px; font-size: 18px; font-weight: 900; color: #ffffff;">
                                ' . e($participant->name ?: 'Peserta Jamaah') . '
                            </div>
                            <div style="margin-top: 6px; font-size: 13px; color: #a1a1aa;">
                                Paket: ' . e($package) . '
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <div style="font-size: 12px; color: #a1a1aa;">WhatsApp</div>
                            <div style="font-size: 14px; font-weight: 800; color: #38bdf8;">' . e($phone) . '</div>
                            <div style="margin-top: 4px; font-size: 12px; color: #a1a1aa;">' . e($email) . '</div>
                        </div>
                    </div>
                </div>
            ';
        }

        $html .= '</div>';

        return new HtmlString($html);
    }

    private static function revisionWhatsappHtml(Registration $record): HtmlString
    {
        $record->loadMissing(['package', 'participants.documents']);

        if ($record->participants->isEmpty()) {
            return new HtmlString(self::emptyBox('Belum ada peserta jamaah untuk dibuatkan link revisi dokumen.'));
        }

        $hasRevision = false;

        $html = '<div style="display: grid; gap: 16px;">';

        foreach ($record->participants as $participant) {
            $revisionDocuments = $participant->documents
                ->filter(fn ($document) => in_array($document->status, ['perlu_revisi', 'ditolak'], true))
                ->values();

            if ($revisionDocuments->isEmpty()) {
                continue;
            }

            $hasRevision = true;

            $participantName = $participant->name ?: 'Peserta Jamaah';
            $phone = self::normalizeWhatsappNumber($participant->phone ?: $record->phone);
            $revisionUrl = $participant->revision_url;

            $message = self::buildRevisionMessage(
                participantName: $participantName,
                record: $record,
                revisionUrl: $revisionUrl,
                revisionDocuments: $revisionDocuments,
            );

            $waUrl = $phone
                ? 'https://wa.me/' . $phone . '?text=' . rawurlencode($message)
                : null;

            $documentList = '<ul style="margin: 10px 0 0 18px; padding: 0; color: #d4d4d8; font-size: 13px; line-height: 1.8;">';

            foreach ($revisionDocuments as $document) {
                $documentList .= '
                    <li>
                        <strong>' . e($document->document_label_value) . '</strong>
                        <span style="color: ' . e($document->status_color_value) . ';">(' . e($document->status_label_value) . ')</span>
                        ' . ($document->note ? '<br><span style="color:#a1a1aa;">Catatan: ' . e($document->note) . '</span>' : '') . '
                    </li>
                ';
            }

            $documentList .= '</ul>';

            $html .= '
                <div style="
                    padding: 18px;
                    border-radius: 20px;
                    border: 1px solid rgba(245, 158, 11, 0.35);
                    background: rgba(245, 158, 11, 0.08);
                ">
                    <div style="
                        display: flex;
                        justify-content: space-between;
                        gap: 14px;
                        flex-wrap: wrap;
                        align-items: flex-start;
                    ">
                        <div style="max-width: 720px;">
                            <div style="font-size: 12px; font-weight: 900; color: #E8BD62; text-transform: uppercase; letter-spacing: .08em;">
                                Perlu Konfirmasi Revisi
                            </div>

                            <div style="margin-top: 6px; font-size: 18px; font-weight: 900; color: #ffffff;">
                                ' . e($participantName) . '
                            </div>

                            <div style="margin-top: 6px; font-size: 13px; color: #a1a1aa;">
                                WhatsApp: ' . e($phone ?: 'Nomor tidak tersedia') . '
                            </div>

                            ' . $documentList . '

                            <div style="
                                margin-top: 14px;
                                padding: 12px;
                                border-radius: 14px;
                                background: rgba(15, 23, 42, 0.55);
                                border: 1px solid rgba(148, 163, 184, 0.20);
                            ">
                                <div style="font-size: 12px; color: #a1a1aa; font-weight: 700;">
                                    Link Revisi Dokumen:
                                </div>
                                <a href="' . e($revisionUrl) . '" target="_blank" style="display: inline-block; margin-top: 5px; color: #38bdf8; font-weight: 800; text-decoration: underline; word-break: break-all;">
                                    ' . e($revisionUrl) . '
                                </a>
                            </div>
                        </div>

                        <div style="
                            display: flex;
                            flex-direction: column;
                            gap: 10px;
                            min-width: 210px;
                        ">
                            ' . ($waUrl ? '
                                <a href="' . e($waUrl) . '" target="_blank" style="
                                    display: inline-flex;
                                    justify-content: center;
                                    align-items: center;
                                    padding: 12px 16px;
                                    border-radius: 14px;
                                    background: #16a34a;
                                    color: #ffffff;
                                    font-weight: 900;
                                    text-decoration: none;
                                    text-align: center;
                                ">
                                    Kirim Revisi via WhatsApp
                                </a>
                            ' : '
                                <div style="
                                    padding: 12px 16px;
                                    border-radius: 14px;
                                    background: rgba(239, 68, 68, 0.12);
                                    color: #f87171;
                                    font-weight: 900;
                                    text-align: center;
                                ">
                                    Nomor WhatsApp Tidak Ada
                                </div>
                            ') . '

                            <a href="' . e($revisionUrl) . '" target="_blank" style="
                                display: inline-flex;
                                justify-content: center;
                                align-items: center;
                                padding: 12px 16px;
                                border-radius: 14px;
                                background: #0B2D5B;
                                color: #ffffff;
                                font-weight: 900;
                                text-decoration: none;
                                text-align: center;
                            ">
                                Buka Link Revisi
                            </a>
                        </div>
                    </div>
                </div>
            ';
        }

        $html .= '</div>';

        if (! $hasRevision) {
            return new HtmlString(self::emptyBox('Belum ada dokumen berstatus Perlu Revisi atau Ditolak. Jika ada dokumen salah, ubah status dokumen terlebih dahulu di Edit Pendaftaran.'));
        }

        return new HtmlString($html);
    }

    private static function documentsSummaryHtml(Registration $record): HtmlString
    {
        $record->loadMissing(['participants.documents']);

        if ($record->participants->isEmpty()) {
            return new HtmlString(self::emptyBox('Belum ada dokumen peserta.'));
        }

        $html = '<div style="display: grid; gap: 18px;">';

        foreach ($record->participants as $participant) {
            $html .= '
                <div style="
                    padding: 18px;
                    border-radius: 20px;
                    border: 1px solid rgba(148, 163, 184, 0.25);
                    background: rgba(255,255,255,0.03);
                ">
                    <div style="font-size: 12px; font-weight: 900; color: #E8BD62; text-transform: uppercase; letter-spacing: .08em;">
                        Peserta ' . e((string) ($participant->order_number ?: '-')) . '
                    </div>

                    <div style="margin-top: 6px; font-size: 18px; font-weight: 900; color: #ffffff;">
                        ' . e($participant->name ?: 'Peserta Jamaah') . '
                    </div>
            ';

            if ($participant->documents->isEmpty()) {
                $html .= self::innerEmptyBox('Belum ada dokumen untuk peserta ini.');
            } else {
                $html .= '
                    <div style="
                        margin-top: 14px;
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                        gap: 12px;
                    ">
                ';

                foreach ($participant->documents as $document) {
                    $fileUrl = $document->file_path ? asset('storage/' . $document->file_path) : null;

                    $html .= '
                        <div style="
                            padding: 14px;
                            border-radius: 16px;
                            border: 1px solid rgba(148, 163, 184, 0.20);
                            background: rgba(15, 23, 42, 0.35);
                        ">
                            <div style="font-size: 13px; font-weight: 900; color: #ffffff;">
                                ' . e($document->document_label_value) . '
                            </div>

                            <div style="
                                display: inline-flex;
                                margin-top: 8px;
                                padding: 5px 10px;
                                border-radius: 999px;
                                background: ' . e($document->status_bg_value) . ';
                                color: ' . e($document->status_color_value) . ';
                                font-size: 11px;
                                font-weight: 900;
                            ">
                                ' . e($document->status_label_value) . '
                            </div>

                            ' . ($document->note ? '
                                <div style="margin-top: 10px; color: #a1a1aa; font-size: 12px; line-height: 1.6;">
                                    Catatan: ' . e($document->note) . '
                                </div>
                            ' : '') . '

                            ' . ($fileUrl ? '
                                <a href="' . e($fileUrl) . '" target="_blank" style="
                                    display: inline-block;
                                    margin-top: 10px;
                                    color: #38bdf8;
                                    font-size: 12px;
                                    font-weight: 800;
                                    text-decoration: underline;
                                ">
                                    Lihat File
                                </a>
                            ' : '
                                <div style="margin-top: 10px; color: #f87171; font-size: 12px; font-weight: 800;">
                                    File belum ada
                                </div>
                            ') . '
                        </div>
                    ';
                }

                $html .= '</div>';
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        return new HtmlString($html);
    }

    private static function buildRevisionMessage(
        string $participantName,
        Registration $record,
        string $revisionUrl,
        $revisionDocuments,
    ): string {
        $packageTitle = $record->package?->title ?: '-';

        $message = "Assalamu’alaikum Bapak/Ibu {$participantName},\n\n";
        $message .= "Kami dari Ajmal Noor Wisata ingin menginformasikan bahwa ada dokumen pendaftaran yang perlu direvisi.\n\n";
        $message .= "Paket: {$packageTitle}\n\n";
        $message .= "Dokumen yang perlu diperbaiki:\n";

        foreach ($revisionDocuments as $document) {
            $message .= "- {$document->document_label_value}: {$document->status_label_value}";

            if ($document->note) {
                $message .= "\n  Catatan: {$document->note}";
            }

            $message .= "\n";
        }

        $message .= "\nSilakan upload ulang dokumen melalui link berikut:\n";
        $message .= "{$revisionUrl}\n\n";
        $message .= "Jamaah tidak perlu daftar ulang. Cukup upload ulang dokumen yang diminta saja.\n\n";
        $message .= "Terima kasih.\n";
        $message .= "Ajmal Noor Wisata\n";
        $message .= "Travel Haji & Umroh";

        return $message;
    }

    private static function normalizeWhatsappNumber(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $number = preg_replace('/[^0-9]/', '', $number);

        if (! $number) {
            return null;
        }

        if (str_starts_with($number, '08')) {
            return '62' . substr($number, 1);
        }

        if (str_starts_with($number, '8')) {
            return '62' . $number;
        }

        return $number;
    }

    private static function statusLabel(?string $status): string
    {
        return match ($status) {
            'baru' => 'Baru',
            'dihubungi' => 'Dihubungi',
            'proses' => 'Proses',
            'dokumen_lengkap' => 'Dokumen Lengkap',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'selesai' => 'Selesai',
            'batal' => 'Batal',
            default => $status ?: '-',
        };
    }

    private static function statusColor(?string $status): string
    {
        return match ($status) {
            'baru' => 'gray',
            'dihubungi' => 'info',
            'proses' => 'warning',
            'dokumen_lengkap' => 'success',
            'menunggu_pembayaran' => 'warning',
            'selesai' => 'success',
            'batal' => 'danger',
            default => 'gray',
        };
    }

    private static function emptyBox(string $message): string
    {
        return '
            <div style="
                padding: 18px;
                border: 1px dashed rgba(148, 163, 184, 0.35);
                border-radius: 18px;
                color: #a1a1aa;
                background: rgba(255,255,255,0.03);
                font-weight: 700;
            ">
                ' . e($message) . '
            </div>
        ';
    }

    private static function innerEmptyBox(string $message): string
    {
        return '
            <div style="
                margin-top: 14px;
                padding: 14px;
                border: 1px dashed rgba(148, 163, 184, 0.35);
                border-radius: 14px;
                color: #a1a1aa;
                background: rgba(255,255,255,0.03);
                font-size: 13px;
                font-weight: 700;
            ">
                ' . e($message) . '
            </div>
        ';
    }
}