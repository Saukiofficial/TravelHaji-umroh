<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepartureGroup;
use App\Support\ReportSetting;
use setasign\Fpdi\Fpdi;
use Throwable;

class DepartureManifestPdfController extends Controller
{
    private ReportSetting $reportSetting;

    public function show(DepartureGroup $departureGroup)
    {
        $this->reportSetting = ReportSetting::make();

        $departureGroup->load([
            'package',
            'participants.registration',
            'participants.participant',
        ]);

        $pdf = new Fpdi();
        $pdf->AddPage('L', 'A4');

        $this->drawHeader($pdf, $departureGroup);
        $this->drawGroupInfo($pdf, $departureGroup);
        $this->drawManifestTable($pdf, $departureGroup);
        $this->drawFooter($pdf);

        $fileName = 'manifest-keberangkatan-' . str($departureGroup->name)->slug('-') . '.pdf';

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    private function drawHeader(Fpdi $pdf, DepartureGroup $group): void
    {
        $pdf->SetFillColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->Rect(0, 0, 297, 38, 'F');

        if ($this->reportSetting->logoPath) {
            try {
                $pdf->Image($this->reportSetting->logoPath, 12, 8, 22, 22);
                $textX = 40;
            } catch (Throwable $e) {
                $textX = 12;
            }
        } else {
            $textX = 12;
        }

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->SetXY($textX, 7);
        $pdf->Cell(175, 8, $this->txt('MANIFEST KEBERANGKATAN JAMAAH'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetX($textX);
        $pdf->Cell(175, 5, $this->txt($this->reportSetting->brandName), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetX($textX);
        $pdf->Cell(175, 5, $this->txt($this->reportSetting->tagline), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetX($textX);
        $pdf->Cell(175, 5, $this->txt($this->reportSetting->contactLine()), 0, 1, 'L');

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(
            $this->reportSetting->accentR,
            $this->reportSetting->accentG,
            $this->reportSetting->accentB
        );

        $pdf->SetXY(210, 8);
        $pdf->Cell(75, 6, $this->txt('Kode: ' . ($group->code ?: '-')), 0, 1, 'R');

        $pdf->SetXY(210, 16);
        $pdf->Cell(75, 6, $this->txt('Status: ' . $this->groupStatusLabel($group->status)), 0, 1, 'R');

        $pdf->SetXY(210, 24);
        $pdf->Cell(75, 6, $this->txt('Cetak: ' . now()->format('d M Y H:i')), 0, 1, 'R');

        $pdf->SetDrawColor(
            $this->reportSetting->accentR,
            $this->reportSetting->accentG,
            $this->reportSetting->accentB
        );
        $pdf->SetLineWidth(0.8);
        $pdf->Line(0, 38, 297, 38);
    }

    private function drawGroupInfo(Fpdi $pdf, DepartureGroup $group): void
    {
        $pdf->SetTextColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->SetFont('Helvetica', 'B', 13);
        $pdf->SetXY(12, 46);
        $pdf->Cell(270, 7, $this->txt($group->name), 0, 1, 'L');

        $pdf->SetDrawColor(
            $this->reportSetting->accentR,
            $this->reportSetting->accentG,
            $this->reportSetting->accentB
        );
        $pdf->Line(12, 56, 285, 56);

        $package = $group->package;

        $leftRows = [
            'Jenis' => $this->groupTypeLabel($group->type),
            'Paket' => $package?->title ?: '-',
            'Tanggal Berangkat' => $group->departure_date ? $group->departure_date->format('d M Y') : '-',
            'Tanggal Pulang' => $group->return_date ? $group->return_date->format('d M Y') : '-',
            'Kuota / Terisi / Sisa' => $group->seat_quota . ' / ' . $group->used_seats . ' / ' . $group->remaining_seats,
        ];

        $middleRows = [
            'Bandara Berangkat' => $group->departure_airport ?: '-',
            'Bandara Tujuan' => $group->arrival_airport ?: '-',
            'Maskapai' => $group->airline ?: '-',
            'Flight Berangkat' => $group->departure_flight_number ?: '-',
            'Flight Pulang' => $group->return_flight_number ?: '-',
        ];

        $rightRows = [
            'Hotel Makkah' => $group->makkah_hotel ?: '-',
            'Hotel Madinah' => $group->madinah_hotel ?: '-',
            'Tour Leader' => trim(($group->tour_leader_name ?: '-') . ' / ' . ($group->tour_leader_phone ?: '-')),
            'Muthawif' => trim(($group->muthawif_name ?: '-') . ' / ' . ($group->muthawif_phone ?: '-')),
            'Meeting Point' => $group->meeting_point ?: '-',
        ];

        $this->drawInfoBox($pdf, $leftRows, 12, 64, 84);
        $this->drawInfoBox($pdf, $middleRows, 106, 64, 84);
        $this->drawInfoBox($pdf, $rightRows, 200, 64, 84);
    }

    private function drawInfoBox(Fpdi $pdf, array $rows, float $x, float $y, float $width): void
    {
        $boxHeight = 50;

        $pdf->SetFillColor(248, 250, 252);
        $pdf->Rect($x, $y - 4, $width, $boxHeight, 'F');

        $pdf->SetDrawColor(225, 225, 225);
        $pdf->SetLineWidth(0.2);
        $pdf->Rect($x, $y - 4, $width, $boxHeight);

        $innerX = $x + 4;
        $currentY = $y;

        foreach ($rows as $label => $value) {
            $pdf->SetFont('Helvetica', 'B', 6.8);
            $pdf->SetTextColor(60, 60, 60);
            $pdf->SetXY($innerX, $currentY);
            $pdf->Cell($width - 8, 3.6, $this->txt($label), 0, 1, 'L');

            $pdf->SetFont('Helvetica', '', 7.4);
            $pdf->SetTextColor(90, 90, 90);
            $pdf->SetX($innerX);

            $text = $this->txt((string) $value);
            $text = strlen($text) > 42 ? substr($text, 0, 39) . '...' : $text;

            $pdf->Cell($width - 8, 4.5, $text, 0, 1, 'L');

            $currentY += 8.8;
        }
    }

    private function drawManifestTable(Fpdi $pdf, DepartureGroup $group): void
    {
        $startY = 128;

        $pdf->SetTextColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetXY(12, 116);
        $pdf->Cell(270, 7, $this->txt('Daftar Peserta Manifest'), 0, 1, 'L');

        $headers = [
            ['No', 9],
            ['Manifest', 20],
            ['Nama Jamaah', 43],
            ['Gender', 18],
            ['No. Paspor', 25],
            ['No. Koper', 22],
            ['Bus', 16],
            ['Kamar', 18],
            ['Visa', 24],
            ['Tiket', 24],
            ['Status', 26],
            ['Catatan', 42],
        ];

        $this->drawTableHeader($pdf, $headers, $startY);

        $y = $startY + 9;
        $no = 1;

        $participants = $group->participants
            ->sortBy(fn ($item) => $item->manifest_number ?: $item->id)
            ->values();

        if ($participants->isEmpty()) {
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetTextColor(90, 90, 90);
            $pdf->SetXY(12, $y);
            $pdf->Cell(287, 8, $this->txt('Belum ada peserta dalam manifest keberangkatan ini.'), 1, 1, 'L');
            return;
        }

        foreach ($participants as $manifest) {
            if ($y > 185) {
                $pdf->AddPage('L', 'A4');
                $this->drawHeader($pdf, $group);

                $pdf->SetTextColor(
                    $this->reportSetting->headerR,
                    $this->reportSetting->headerG,
                    $this->reportSetting->headerB
                );
                $pdf->SetFont('Helvetica', 'B', 12);
                $pdf->SetXY(12, 50);
                $pdf->Cell(270, 7, $this->txt('Lanjutan Daftar Peserta Manifest'), 0, 1, 'L');

                $this->drawTableHeader($pdf, $headers, 62);
                $y = 71;
            }

            $participant = $manifest->participant;

            $row = [
                (string) $no,
                $manifest->manifest_number ?: '-',
                $participant?->name ?: '-',
                $participant?->gender ?: '-',
                $participant?->passport_number ?: '-',
                $manifest->baggage_number ?: '-',
                $manifest->bus_number ?: '-',
                $manifest->room_number ?: '-',
                $this->visaStatusLabel($manifest->visa_status),
                $this->ticketStatusLabel($manifest->ticket_status),
                $this->departureStatusLabel($manifest->departure_status),
                $manifest->notes ?: '-',
            ];

            $this->drawTableRow($pdf, $headers, $row, $y, $no);

            $y += 8;
            $no++;
        }
    }

    private function drawTableHeader(Fpdi $pdf, array $headers, float $y): void
    {
        $x = 12;

        $pdf->SetFillColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetDrawColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->SetFont('Helvetica', 'B', 7);

        foreach ($headers as [$label, $width]) {
            $pdf->SetXY($x, $y);
            $pdf->Cell($width, 9, $this->txt($label), 1, 0, 'C', true);
            $x += $width;
        }
    }

    private function drawTableRow(Fpdi $pdf, array $headers, array $row, float $y, int $no): void
    {
        $x = 12;

        if ($no % 2 === 0) {
            $pdf->SetFillColor(248, 250, 252);
        } else {
            $pdf->SetFillColor(255, 255, 255);
        }

        $pdf->SetTextColor(65, 65, 65);
        $pdf->SetDrawColor(225, 225, 225);
        $pdf->SetFont('Helvetica', '', 6.5);

        foreach ($headers as $index => [$label, $width]) {
            $text = $this->txt((string) ($row[$index] ?? '-'));

            $limit = match ($label) {
                'Nama Jamaah' => 31,
                'Catatan' => 32,
                'No. Paspor' => 18,
                default => 14,
            };

            if (strlen($text) > $limit) {
                $text = substr($text, 0, $limit - 3) . '...';
            }

            $align = in_array($label, ['No', 'Gender', 'Bus', 'Kamar'], true) ? 'C' : 'L';

            $pdf->SetXY($x, $y);
            $pdf->Cell($width, 8, $text, 1, 0, $align, true);
            $x += $width;
        }
    }

    private function drawFooter(Fpdi $pdf): void
    {
        $pdf->SetDrawColor(225, 225, 225);
        $pdf->SetLineWidth(0.2);
        $pdf->Line(12, 198, 285, 198);

        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetTextColor(120, 120, 120);

        $footer = $this->reportSetting->titleLine();

        if ($this->reportSetting->address && $this->reportSetting->address !== '-') {
            $footer .= ' | ' . $this->reportSetting->address;
        }

        $pdf->SetXY(12, 201);
        $pdf->Cell(273, 5, $this->txt($footer), 0, 1, 'C');
    }

    private function groupTypeLabel(?string $type): string
    {
        return [
            'umroh' => 'Umroh',
            'haji' => 'Haji',
        ][$type] ?? '-';
    }

    private function groupStatusLabel(?string $status): string
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

    private function txt(?string $text): string
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $text ?? '');
    }
}