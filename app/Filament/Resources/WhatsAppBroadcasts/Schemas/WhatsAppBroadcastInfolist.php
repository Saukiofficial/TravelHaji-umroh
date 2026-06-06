<?php

namespace App\Filament\Resources\WhatsAppBroadcasts\Schemas;

use App\Models\WhatsAppBroadcast;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class WhatsAppBroadcastInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Broadcast')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Judul Broadcast'),

                        TextEntry::make('status_label')
                            ->label('Status')
                            ->badge()
                            ->color(fn (WhatsAppBroadcast $record): string => self::statusColor($record->status)),

                        TextEntry::make('total_recipients')
                            ->label('Total Penerima')
                            ->numeric()
                            ->suffix(' Jamaah'),

                        TextEntry::make('sent_at')
                            ->label('Tanggal Selesai')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('message')
                            ->label('Template Pesan')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Update')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 4,
                    ]),

                Section::make('Daftar Penerima & Tombol WhatsApp')
                    ->description('Klik tombol WhatsApp pada masing-masing jamaah untuk mengirim pesan broadcast.')
                    ->schema([
                        TextEntry::make('recipients_html')
                            ->label('')
                            ->state(fn (WhatsAppBroadcast $record): HtmlString => self::recipientsHtml($record))
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function recipientsHtml(WhatsAppBroadcast $record): HtmlString
    {
        $record->loadMissing(['recipients.participant.registration.package']);

        if ($record->recipients->isEmpty()) {
            return new HtmlString('
                <div style="
                    padding: 18px;
                    border: 1px dashed rgba(148, 163, 184, 0.35);
                    border-radius: 18px;
                    color: #a1a1aa;
                    background: rgba(255,255,255,0.03);
                    font-weight: 700;
                ">
                    Belum ada penerima. Silakan edit broadcast dan pilih jamaah penerima.
                </div>
            ');
        }

        $html = '<div style="display: grid; gap: 14px;">';

        foreach ($record->recipients as $recipient) {
            $statusLabel = match ($recipient->status) {
                'clicked' => 'Sudah Dibuka',
                'failed' => 'Gagal',
                default => 'Siap Dikirim',
            };

            $statusColor = match ($recipient->status) {
                'clicked' => '#22c55e',
                'failed' => '#ef4444',
                default => '#38bdf8',
            };

            $html .= '
                <div style="
                    padding: 18px;
                    border-radius: 20px;
                    border: 1px solid rgba(148, 163, 184, 0.25);
                    background: rgba(255,255,255,0.03);
                ">
                    <div style="
                        display: flex;
                        justify-content: space-between;
                        gap: 16px;
                        flex-wrap: wrap;
                        align-items: flex-start;
                    ">
                        <div style="max-width: 760px;">
                            <div style="font-size: 12px; font-weight: 900; color: #E8BD62; text-transform: uppercase; letter-spacing: .08em;">
                                Penerima Broadcast
                            </div>

                            <div style="margin-top: 6px; font-size: 18px; font-weight: 900; color: #ffffff;">
                                ' . e($recipient->recipient_name ?: 'Jamaah') . '
                            </div>

                            <div style="margin-top: 6px; font-size: 13px; color: #a1a1aa;">
                                WhatsApp: <strong style="color:#38bdf8;">' . e($recipient->recipient_phone ?: '-') . '</strong>
                            </div>

                            <div style="
                                display: inline-flex;
                                margin-top: 10px;
                                padding: 5px 10px;
                                border-radius: 999px;
                                background: rgba(56, 189, 248, 0.10);
                                color: ' . e($statusColor) . ';
                                font-size: 11px;
                                font-weight: 900;
                            ">
                                ' . e($statusLabel) . '
                            </div>

                            <div style="
                                margin-top: 14px;
                                padding: 12px;
                                border-radius: 14px;
                                background: rgba(15, 23, 42, 0.55);
                                border: 1px solid rgba(148, 163, 184, 0.20);
                                color: #d4d4d8;
                                font-size: 13px;
                                line-height: 1.8;
                                white-space: pre-line;
                            ">' . e($recipient->final_message ?: '-') . '</div>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:10px; min-width:220px;">
                            ' . ($recipient->wa_url ? '
                                <a href="' . e($recipient->wa_url) . '" target="_blank" style="
                                    display:inline-flex;
                                    justify-content:center;
                                    align-items:center;
                                    padding:12px 16px;
                                    border-radius:14px;
                                    background:#16a34a;
                                    color:#ffffff;
                                    font-weight:900;
                                    text-decoration:none;
                                    text-align:center;
                                ">
                                    Kirim via WhatsApp
                                </a>
                            ' : '
                                <div style="
                                    padding:12px 16px;
                                    border-radius:14px;
                                    background:rgba(239,68,68,.12);
                                    color:#f87171;
                                    font-weight:900;
                                    text-align:center;
                                ">
                                    Nomor Tidak Tersedia
                                </div>
                            ') . '
                        </div>
                    </div>
                </div>
            ';
        }

        $html .= '</div>';

        return new HtmlString($html);
    }

    private static function statusColor(?string $status): string
    {
        return match ($status) {
            'ready' => 'warning',
            'sent' => 'success',
            default => 'gray',
        };
    }
}