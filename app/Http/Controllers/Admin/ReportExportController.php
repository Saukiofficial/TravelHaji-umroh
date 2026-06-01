<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepartureGroup;
use App\Models\Registration;
use App\Models\RegistrationPayment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    public function registrations(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Pendaftaran Jamaah');

        $headers = [
            'ID',
            'Nama Pendaftar',
            'WhatsApp',
            'Email',
            'Paket',
            'Jumlah Peserta',
            'Status Pendaftaran',
            'Total Tagihan',
            'Total Bayar Valid',
            'Sisa Tagihan',
            'Status Pembayaran',
            'Tanggal Daftar',
        ];

        $this->buildTitle($sheet, 'LAPORAN PENDAFTARAN JAMAAH', count($headers));
        $this->buildHeader($sheet, $headers, 4);

        $row = 5;

        Registration::query()
            ->with(['package', 'payments'])
            ->latest()
            ->chunk(200, function ($registrations) use ($sheet, &$row) {
                foreach ($registrations as $registration) {
                    $sheet->fromArray([
                        $registration->id,
                        $registration->name,
                        $registration->phone,
                        $registration->email ?: '-',
                        $registration->package?->title ?? '-',
                        $registration->total_participants,
                        $this->registrationStatusLabel($registration->status),
                        (float) $registration->total_bill,
                        (float) $registration->total_paid,
                        (float) $registration->remaining_payment,
                        $this->paymentStatusLabel($registration->payment_status),
                        optional($registration->created_at)->format('d-m-Y H:i'),
                    ], null, 'A' . $row);

                    $row++;
                }
            });

        $this->styleTable($sheet, count($headers), 4, $row - 1);
        $this->styleMoneyColumns($sheet, ['H', 'I', 'J'], 5, $row - 1);
        $this->autoSizeColumns($sheet, count($headers));

        return $this->download($spreadsheet, 'laporan-pendaftaran-jamaah-' . now()->format('Y-m-d-His') . '.xlsx');
    }

    public function payments(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Pembayaran Jamaah');

        $headers = [
            'ID',
            'Kode Pembayaran',
            'Nama Pendaftar',
            'Paket',
            'Jenis Pembayaran',
            'Nominal',
            'Metode Pembayaran',
            'Status Verifikasi',
            'Tanggal Bayar',
            'Tanggal Verifikasi',
            'Catatan',
        ];

        $this->buildTitle($sheet, 'LAPORAN PEMBAYARAN JAMAAH', count($headers));
        $this->buildHeader($sheet, $headers, 4);

        $row = 5;

        RegistrationPayment::query()
            ->with(['registration.package'])
            ->latest()
            ->chunk(200, function ($payments) use ($sheet, &$row) {
                foreach ($payments as $payment) {
                    $sheet->fromArray([
                        $payment->id,
                        $payment->payment_code ?: '-',
                        $payment->registration?->name ?? '-',
                        $payment->registration?->package?->title ?? '-',
                        $this->paymentTypeLabel($payment->payment_type),
                        (float) $payment->amount,
                        $this->paymentMethodLabel($payment->payment_method),
                        $this->paymentVerificationStatusLabel($payment->status),
                        optional($payment->paid_at)->format('d-m-Y'),
                        optional($payment->verified_at)->format('d-m-Y H:i'),
                        $payment->note ?: '-',
                    ], null, 'A' . $row);

                    $row++;
                }
            });

        $this->styleTable($sheet, count($headers), 4, $row - 1);
        $this->styleMoneyColumns($sheet, ['F'], 5, $row - 1);
        $this->autoSizeColumns($sheet, count($headers));

        return $this->download($spreadsheet, 'laporan-pembayaran-jamaah-' . now()->format('Y-m-d-His') . '.xlsx');
    }

    public function manifests(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Manifest Keberangkatan');

        $headers = [
            'ID Grup',
            'Nama Grup',
            'Kode Grup',
            'Jenis',
            'Status Grup',
            'Paket',
            'Tanggal Berangkat',
            'Tanggal Pulang',
            'Maskapai',
            'Flight Berangkat',
            'Flight Pulang',
            'Tour Leader',
            'Nama Jamaah',
            'Gender',
            'Nomor Paspor',
            'Nomor Manifest',
            'Nomor Koper',
            'Bus',
            'Kamar',
            'Tipe Kamar',
            'Status Visa',
            'Nomor Visa',
            'Status Tiket',
            'Nomor Tiket',
            'Kode Booking',
            'Status Keberangkatan',
            'Catatan',
        ];

        $this->buildTitle($sheet, 'LAPORAN MANIFEST KEBERANGKATAN', count($headers));
        $this->buildHeader($sheet, $headers, 4);

        $row = 5;

        DepartureGroup::query()
            ->with([
                'package',
                'participants.participant',
                'participants.registration',
            ])
            ->latest()
            ->chunk(50, function ($groups) use ($sheet, &$row) {
                foreach ($groups as $group) {
                    if ($group->participants->isEmpty()) {
                        $sheet->fromArray([
                            $group->id,
                            $group->name,
                            $group->code ?: '-',
                            $this->departureTypeLabel($group->type),
                            $this->departureGroupStatusLabel($group->status),
                            $group->package?->title ?? '-',
                            optional($group->departure_date)->format('d-m-Y'),
                            optional($group->return_date)->format('d-m-Y'),
                            $group->airline ?: '-',
                            $group->departure_flight_number ?: '-',
                            $group->return_flight_number ?: '-',
                            $group->tour_leader_name ?: '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            '-',
                            $group->notes ?: '-',
                        ], null, 'A' . $row);

                        $row++;
                        continue;
                    }

                    foreach ($group->participants as $manifest) {
                        $participant = $manifest->participant;

                        $sheet->fromArray([
                            $group->id,
                            $group->name,
                            $group->code ?: '-',
                            $this->departureTypeLabel($group->type),
                            $this->departureGroupStatusLabel($group->status),
                            $group->package?->title ?? '-',
                            optional($group->departure_date)->format('d-m-Y'),
                            optional($group->return_date)->format('d-m-Y'),
                            $group->airline ?: '-',
                            $group->departure_flight_number ?: '-',
                            $group->return_flight_number ?: '-',
                            $group->tour_leader_name ?: '-',
                            $participant?->name ?? '-',
                            $participant?->gender ?? '-',
                            $participant?->passport_number ?? '-',
                            $manifest->manifest_number ?: '-',
                            $manifest->baggage_number ?: '-',
                            $manifest->bus_number ?: '-',
                            $manifest->room_number ?: '-',
                            $this->roomTypeLabel($manifest->room_type),
                            $this->visaStatusLabel($manifest->visa_status),
                            $manifest->visa_number ?: '-',
                            $this->ticketStatusLabel($manifest->ticket_status),
                            $manifest->ticket_number ?: '-',
                            $manifest->booking_code ?: '-',
                            $this->departureStatusLabel($manifest->departure_status),
                            $manifest->notes ?: '-',
                        ], null, 'A' . $row);

                        $row++;
                    }
                }
            });

        $this->styleTable($sheet, count($headers), 4, $row - 1);
        $this->autoSizeColumns($sheet, count($headers));

        return $this->download($spreadsheet, 'laporan-manifest-keberangkatan-' . now()->format('Y-m-d-His') . '.xlsx');
    }

    private function buildTitle($sheet, string $title, int $totalColumns): void
    {
        $lastColumn = Coordinate::stringFromColumnIndex($totalColumns);

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', $title);

        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', 'Ajmal Noor Wisata - Travel Haji & Umroh | Dicetak: ' . now()->format('d-m-Y H:i'));

        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '004F41'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => ['rgb' => '6B7280'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->getRowDimension(2)->setRowHeight(22);
    }

    private function buildHeader($sheet, array $headers, int $row): void
    {
        $sheet->fromArray($headers, null, 'A' . $row);

        $lastColumn = Coordinate::stringFromColumnIndex(count($headers));

        $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '003F35'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8BD62'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'B7791F'],
                ],
            ],
        ]);

        $sheet->getRowDimension($row)->setRowHeight(28);
        $sheet->freezePane('A5');
    }

    private function styleTable($sheet, int $totalColumns, int $headerRow, int $lastRow): void
    {
        if ($lastRow < $headerRow) {
            return;
        }

        $lastColumn = Coordinate::stringFromColumnIndex($totalColumns);

        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
        ]);

        for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8FAFC'],
                    ],
                ]);
            }
        }

        $sheet->setAutoFilter("A{$headerRow}:{$lastColumn}{$lastRow}");
    }

    private function styleMoneyColumns($sheet, array $columns, int $startRow, int $lastRow): void
    {
        if ($lastRow < $startRow) {
            return;
        }

        foreach ($columns as $column) {
            $sheet->getStyle("{$column}{$startRow}:{$column}{$lastRow}")
                ->getNumberFormat()
                ->setFormatCode('"Rp" #,##0');
        }
    }

    private function autoSizeColumns($sheet, int $totalColumns): void
    {
        for ($i = 1; $i <= $totalColumns; $i++) {
            $column = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    private function download(Spreadsheet $spreadsheet, string $fileName): StreamedResponse
    {
        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function registrationStatusLabel(?string $status): string
    {
        return [
            'baru' => 'Baru',
            'dihubungi' => 'Dihubungi',
            'proses' => 'Proses',
            'dokumen_lengkap' => 'Dokumen Lengkap',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'selesai' => 'Selesai',
            'batal' => 'Batal',
        ][$status] ?? '-';
    }

    private function paymentStatusLabel(?string $status): string
    {
        return [
            'belum_ada_tagihan' => 'Belum Ada Tagihan',
            'belum_bayar' => 'Belum Bayar',
            'sebagian' => 'Bayar Sebagian',
            'lunas' => 'Lunas',
        ][$status] ?? '-';
    }

    private function paymentTypeLabel(?string $type): string
    {
        return [
            'dp' => 'DP / Uang Muka',
            'cicilan' => 'Cicilan',
            'pelunasan' => 'Pelunasan',
            'tambahan' => 'Biaya Tambahan',
            'refund' => 'Refund / Pengembalian',
        ][$type] ?? '-';
    }

    private function paymentMethodLabel(?string $method): string
    {
        return [
            'transfer_bank' => 'Transfer Bank',
            'cash' => 'Cash / Tunai',
            'qris' => 'QRIS',
            'lainnya' => 'Lainnya',
        ][$method] ?? '-';
    }

    private function paymentVerificationStatusLabel(?string $status): string
    {
        return [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'valid' => 'Valid',
            'ditolak' => 'Ditolak',
        ][$status] ?? '-';
    }

    private function departureTypeLabel(?string $type): string
    {
        return [
            'umroh' => 'Umroh',
            'haji' => 'Haji',
        ][$type] ?? '-';
    }

    private function departureGroupStatusLabel(?string $status): string
    {
        return [
            'draft' => 'Draft',
            'open' => 'Dibuka',
            'full' => 'Penuh',
            'departed' => 'Sudah Berangkat',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ][$status] ?? '-';
    }

    private function roomTypeLabel(?string $type): string
    {
        return [
            'single' => 'Single',
            'double' => 'Double',
            'triple' => 'Triple',
            'quad' => 'Quad',
            'family' => 'Family',
        ][$type] ?? '-';
    }

    private function visaStatusLabel(?string $status): string
    {
        return [
            'belum_diajukan' => 'Belum Diajukan',
            'proses' => 'Proses',
            'terbit' => 'Terbit',
            'ditolak' => 'Ditolak',
        ][$status] ?? '-';
    }

    private function ticketStatusLabel(?string $status): string
    {
        return [
            'belum_dipesan' => 'Belum Dipesan',
            'proses' => 'Proses',
            'issued' => 'Issued',
            'cancelled' => 'Cancelled',
        ][$status] ?? '-';
    }

    private function departureStatusLabel(?string $status): string
    {
        return [
            'terdaftar' => 'Terdaftar',
            'siap_berangkat' => 'Siap Berangkat',
            'berangkat' => 'Berangkat',
            'selesai' => 'Selesai',
            'batal' => 'Batal',
        ][$status] ?? '-';
    }
}