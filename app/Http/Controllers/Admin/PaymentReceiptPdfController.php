<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationPayment;
use App\Support\ReportSetting;
use setasign\Fpdi\Fpdi;
use Throwable;

class PaymentReceiptPdfController extends Controller
{
    private ReportSetting $reportSetting;

    public function show(RegistrationPayment $payment)
    {
        $this->reportSetting = ReportSetting::make();

        $payment->load([
            'registration.package',
            'registration.participants',
            'registration.payments',
        ]);

        $registration = $payment->registration;
        $package = $registration?->package;

        $pdf = new Fpdi();
        $pdf->AddPage('P', 'A4');

        $this->drawHeader($pdf, 'KWITANSI PEMBAYARAN');
        $this->drawReceiptTitle($pdf, $payment);
        $this->drawCustomerInfo($pdf, $registration, $package);
        $this->drawPaymentInfo($pdf, $payment);
        $this->drawSummary($pdf, $registration);
        $this->drawSignature($pdf);
        $this->drawFooter($pdf);

        $fileName = 'kwitansi-' . ($payment->payment_code ?: 'pembayaran-' . $payment->id) . '.pdf';

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    private function drawHeader(Fpdi $pdf, string $title): void
    {
        $pdf->SetFillColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->Rect(0, 0, 210, 46, 'F');

        if ($this->reportSetting->logoPath) {
            try {
                $pdf->Image($this->reportSetting->logoPath, 15, 9, 23, 23);
                $textX = 43;
            } catch (Throwable $e) {
                $textX = 15;
            }
        } else {
            $textX = 15;
        }

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 17);
        $pdf->SetXY($textX, 8);
        $pdf->Cell(150, 8, $this->txt($this->reportSetting->brandName), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetX($textX);
        $pdf->Cell(150, 5, $this->txt($this->reportSetting->tagline), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 7.5);
        $pdf->SetX($textX);
        $pdf->Cell(150, 5, $this->txt($this->reportSetting->contactLine()), 0, 1, 'L');

        $address = $this->reportSetting->address !== '-' ? $this->reportSetting->address : '';
        if ($address) {
            $pdf->SetFont('Helvetica', '', 7);
            $pdf->SetX($textX);
            $pdf->Cell(150, 5, $this->txt($address), 0, 1, 'L');
        }

        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetTextColor(
            $this->reportSetting->accentR,
            $this->reportSetting->accentG,
            $this->reportSetting->accentB
        );
        $pdf->SetXY(145, 15);
        $pdf->Cell(50, 7, $this->txt($title), 0, 1, 'R');

        $pdf->SetDrawColor(
            $this->reportSetting->accentR,
            $this->reportSetting->accentG,
            $this->reportSetting->accentB
        );
        $pdf->SetLineWidth(0.8);
        $pdf->Line(0, 46, 210, 46);
    }

    private function drawReceiptTitle(Fpdi $pdf, RegistrationPayment $payment): void
    {
        $pdf->SetTextColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->SetFont('Helvetica', 'B', 18);
        $pdf->SetXY(15, 58);
        $pdf->Cell(120, 8, $this->txt('KWITANSI PEMBAYARAN'), 0, 0, 'L');

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor(90, 90, 90);
        $pdf->SetXY(135, 58);
        $pdf->Cell(60, 8, $this->txt('No: ' . $this->receiptNumber($payment)), 0, 1, 'R');

        $pdf->SetDrawColor(
            $this->reportSetting->accentR,
            $this->reportSetting->accentG,
            $this->reportSetting->accentB
        );
        $pdf->Line(15, 71, 195, 71);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->SetXY(15, 74);
        $pdf->Cell(180, 6, $this->txt('Tanggal Cetak: ' . now()->format('d M Y H:i')), 0, 1, 'R');
    }

    private function drawCustomerInfo(Fpdi $pdf, $registration, $package): void
    {
        $pdf->SetTextColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->SetFont('Helvetica', 'B', 13);
        $pdf->SetXY(15, 87);
        $pdf->Cell(180, 7, $this->txt('Data Pendaftar'), 0, 1, 'L');

        $rows = [
            'Nama Pendaftar' => $registration?->name ?: '-',
            'Nomor WhatsApp' => $registration?->phone ?: '-',
            'Email' => $registration?->email ?: '-',
            'Jumlah Peserta' => ($registration?->total_participants ?: 1) . ' Orang',
            'Paket' => $package?->title ?: '-',
            'Jenis Paket' => $package?->type ? strtoupper($package->type) : '-',
        ];

        $this->drawRows($pdf, $rows, 101);
    }

    private function drawPaymentInfo(Fpdi $pdf, RegistrationPayment $payment): void
    {
        $pdf->SetTextColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->SetFont('Helvetica', 'B', 13);
        $pdf->SetXY(15, 155);
        $pdf->Cell(180, 7, $this->txt('Detail Pembayaran'), 0, 1, 'L');

        $pdf->SetFillColor(248, 244, 236);
        $pdf->Rect(15, 169, 180, 55, 'F');

        $pdf->SetDrawColor(
            $this->reportSetting->accentR,
            $this->reportSetting->accentG,
            $this->reportSetting->accentB
        );
        $pdf->SetLineWidth(0.3);
        $pdf->Rect(15, 169, 180, 55);

        $pdf->SetTextColor(80, 80, 80);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(25, 177);
        $pdf->Cell(60, 7, $this->txt('Jenis Pembayaran'), 0, 0, 'L');

        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetTextColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->Cell(100, 7, $this->txt($this->paymentTypeLabel($payment->payment_type)), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->SetX(25);
        $pdf->Cell(60, 7, $this->txt('Nominal'), 0, 0, 'L');

        $pdf->SetFont('Helvetica', 'B', 19);
        $pdf->SetTextColor(
            $this->reportSetting->accentR,
            $this->reportSetting->accentG,
            $this->reportSetting->accentB
        );
        $pdf->Cell(100, 8, $this->txt($this->formatRupiah($payment->amount)), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->SetX(25);
        $pdf->Cell(60, 7, $this->txt('Metode Pembayaran'), 0, 0, 'L');

        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetTextColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->Cell(100, 7, $this->txt($this->paymentMethodLabel($payment->payment_method)), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->SetX(25);
        $pdf->Cell(60, 7, $this->txt('Tanggal Bayar'), 0, 0, 'L');

        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetTextColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->Cell(100, 7, $this->txt($payment->paid_at ? $payment->paid_at->format('d M Y') : '-'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->SetX(25);
        $pdf->Cell(60, 7, $this->txt('Status Verifikasi'), 0, 0, 'L');

        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetTextColor(
            $payment->status === 'valid' ? 34 : 180,
            $payment->status === 'valid' ? 197 : 30,
            $payment->status === 'valid' ? 94 : 30
        );
        $pdf->Cell(100, 7, $this->txt($this->paymentStatusLabel($payment->status)), 0, 1, 'L');

        if ($payment->note) {
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->SetXY(25, 225);
            $pdf->MultiCell(160, 5, $this->txt('Catatan: ' . $payment->note));
        }
    }

    private function drawSummary(Fpdi $pdf, $registration): void
    {
        if (! $registration) {
            return;
        }

        $registration->loadMissing(['package', 'payments']);

        $pdf->SetTextColor(
            $this->reportSetting->headerR,
            $this->reportSetting->headerG,
            $this->reportSetting->headerB
        );
        $pdf->SetFont('Helvetica', 'B', 13);
        $pdf->SetXY(15, 236);
        $pdf->Cell(180, 7, $this->txt('Ringkasan Tagihan'), 0, 1, 'L');

        $summaryRows = [
            'Total Tagihan' => $this->formatRupiah($registration->total_bill),
            'Total Bayar Valid' => $this->formatRupiah($registration->total_paid),
            'Refund Valid' => $this->formatRupiah($registration->total_refund),
            'Sisa Tagihan' => $this->formatRupiah($registration->remaining_payment),
        ];

        $this->drawSummaryTable($pdf, $summaryRows, 249);
    }

    private function drawSignature(Fpdi $pdf): void
    {
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(80, 80, 80);

        $pdf->SetXY(135, 238);
        $pdf->Cell(60, 6, $this->txt('Petugas Admin,'), 0, 1, 'C');

        $pdf->SetXY(135, 265);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(60, 6, $this->txt($this->reportSetting->brandName), 0, 1, 'C');
    }

    private function drawFooter(Fpdi $pdf): void
    {
        $pdf->SetDrawColor(225, 225, 225);
        $pdf->SetLineWidth(0.2);
        $pdf->Line(15, 281, 195, 281);

        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetTextColor(120, 120, 120);

        $footer = 'Kwitansi ini dicetak otomatis dari sistem admin ' . $this->reportSetting->brandName . '.';

        $pdf->SetXY(15, 284);
        $pdf->Cell(180, 5, $this->txt($footer), 0, 1, 'C');
    }

    private function drawRows(Fpdi $pdf, array $rows, float $startY): void
    {
        $y = $startY;

        foreach ($rows as $label => $value) {
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->SetXY(15, $y);
            $pdf->Cell(45, 7, $this->txt($label), 0, 0, 'L');

            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetTextColor(70, 70, 70);
            $pdf->SetXY(65, $y);

            $text = $this->txt((string) ($value ?: '-'));

            if (strlen($text) > 70) {
                $pdf->MultiCell(125, 6, $text);
                $y = $pdf->GetY() + 1;
            } else {
                $pdf->Cell(125, 7, $text, 0, 1, 'L');
                $y += 8;
            }
        }
    }

    private function drawSummaryTable(Fpdi $pdf, array $rows, float $startY): void
    {
        $y = $startY;

        foreach ($rows as $label => $value) {
            $pdf->SetDrawColor(230, 230, 230);
            $pdf->SetFillColor(250, 250, 250);

            $pdf->SetFont('Helvetica', 'B', 8.5);
            $pdf->SetTextColor(70, 70, 70);
            $pdf->SetXY(15, $y);
            $pdf->Cell(75, 8, $this->txt($label), 1, 0, 'L', true);

            $pdf->SetFont('Helvetica', 'B', 8.5);
            $pdf->SetTextColor(
                $this->reportSetting->headerR,
                $this->reportSetting->headerG,
                $this->reportSetting->headerB
            );
            $pdf->Cell(40, 8, $this->txt($value), 1, 1, 'R', true);

            $y += 8;
        }
    }

    private function receiptNumber(RegistrationPayment $payment): string
    {
        if ($payment->payment_code) {
            return $payment->payment_code;
        }

        return 'KW-' . str_pad((string) $payment->id, 5, '0', STR_PAD_LEFT);
    }

    private function formatRupiah($value): string
    {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
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

    private function paymentStatusLabel(?string $status): string
    {
        return [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'valid' => 'Valid',
            'ditolak' => 'Ditolak',
        ][$status] ?? '-';
    }

    private function txt(?string $text): string
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $text ?? '');
    }
}