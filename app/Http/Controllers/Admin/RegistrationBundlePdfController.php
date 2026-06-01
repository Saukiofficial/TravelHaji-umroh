<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationParticipant;
use App\Support\ReportSetting;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Throwable;

class RegistrationBundlePdfController extends Controller
{
    private ReportSetting $reportSetting;

    public function show(Registration $registration)
    {
        $this->reportSetting = ReportSetting::make();

        $registration->load([
            'package',
            'participants.documents',
            'documents',
        ]);

        $pdf = new Fpdi();

        $this->addCoverPage($pdf, $registration);

        if ($registration->participants->isNotEmpty()) {
            foreach ($registration->participants->sortBy('order_number') as $participant) {
                $this->addParticipantPage($pdf, $registration, $participant);

                foreach ($participant->documents as $document) {
                    if (! $document->file_path) {
                        continue;
                    }

                    $this->addDocumentToPdf(
                        pdf: $pdf,
                        path: $document->file_path,
                        title: 'Peserta ' . $participant->order_number . ' - ' . $this->documentLabel($document->document_type),
                        status: $this->statusLabel($document->status),
                        note: $document->note
                    );
                }
            }
        } else {
            $this->addErrorPage(
                $pdf,
                'Data Peserta Jamaah',
                'Belum ada data peserta jamaah pada pendaftaran ini.'
            );
        }

        $fileName = 'berkas-pendaftaran-' . str($registration->name)->slug('-') . '.pdf';

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    private function addCoverPage(Fpdi $pdf, Registration $registration): void
    {
        $pdf->AddPage('P', 'A4');

        $this->addHeader($pdf, 'BERKAS PENDAFTARAN JAMAAH');

        $pdf->SetTextColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->SetFont('Helvetica', 'B', 15);
        $pdf->SetXY(15, 58);
        $pdf->Cell(180, 8, $this->txt('Data Booking / Pendaftar Utama'), 0, 1, 'L');

        $pdf->SetDrawColor($this->reportSetting->accentR, $this->reportSetting->accentG, $this->reportSetting->accentB);
        $pdf->Line(15, 69, 195, 69);

        $rows = [
            'Nama Pendaftar' => $registration->name,
            'Nomor WhatsApp' => $registration->phone,
            'Email' => $registration->email ?: '-',
            'Jumlah Peserta' => $registration->total_participants . ' Orang',
            'Status Pendaftaran' => $this->registrationStatusLabel($registration->status),
            'Tanggal Daftar' => optional($registration->created_at)->format('d M Y H:i') ?: '-',
            'Alamat' => $registration->address ?: '-',
            'Catatan' => $registration->note ?: '-',
        ];

        $this->addRows($pdf, $rows, 81);

        $pdf->SetTextColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->SetFont('Helvetica', 'B', 15);
        $pdf->SetXY(15, 170);
        $pdf->Cell(180, 8, $this->txt('Ringkasan Paket'), 0, 1, 'L');

        $pdf->SetDrawColor($this->reportSetting->accentR, $this->reportSetting->accentG, $this->reportSetting->accentB);
        $pdf->Line(15, 181, 195, 181);

        $package = $registration->package;

        $packageRows = [
            'Nama Paket' => $package?->title ?: '-',
            'Jenis Paket' => $package?->type ? strtoupper($package->type) : '-',
            'Harga Paket' => $package?->price ? 'Rp ' . number_format((float) $package->price, 0, ',', '.') : '-',
            'Durasi' => $package?->duration_days ? $package->duration_days . ' Hari' : '-',
            'Tanggal Berangkat' => $package?->departure_date ?: '-',
            'Maskapai' => $package?->airline ?: '-',
            'Hotel Makkah' => $package?->makkah_hotel ?: '-',
            'Hotel Madinah' => $package?->madinah_hotel ?: '-',
            'Seat' => $package?->seat ? $package->seat . ' Seat' : '-',
        ];

        $this->addRows($pdf, $packageRows, 193);

        $this->addFooter($pdf);
    }

    private function addParticipantPage(
        Fpdi $pdf,
        Registration $registration,
        RegistrationParticipant $participant
    ): void {
        $pdf->AddPage('P', 'A4');

        $this->addHeader(
            $pdf,
            'DATA PESERTA ' . $participant->order_number
        );

        $pdf->SetTextColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->SetFont('Helvetica', 'B', 15);
        $pdf->SetXY(15, 58);
        $pdf->Cell(180, 8, $this->txt('Data Diri Jamaah'), 0, 1, 'L');

        $pdf->SetDrawColor($this->reportSetting->accentR, $this->reportSetting->accentG, $this->reportSetting->accentB);
        $pdf->Line(15, 69, 195, 69);

        $this->addParticipantPhotoBox($pdf, $participant, 150, 78);

        $rows = [
            'Urutan Peserta' => 'Peserta ' . $participant->order_number,
            'Nama Lengkap' => $participant->name,
            'Jenis Kelamin' => $participant->gender ?: '-',
            'Tempat Lahir' => $participant->birth_place ?: '-',
            'Tanggal Lahir' => $participant->birth_date ? $participant->birth_date->format('d M Y') : '-',
            'NIK' => $participant->nik ?: '-',
            'Nomor WhatsApp' => $participant->phone ?: '-',
            'Email' => $participant->email ?: '-',
            'Alamat' => $participant->address ?: '-',
        ];

        $y = 81;

        foreach ($rows as $label => $value) {
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetTextColor(30, 30, 30);
            $pdf->SetXY(15, $y);
            $pdf->Cell(48, 7, $this->txt($label), 0, 0, 'L');

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetTextColor(55, 55, 55);
            $pdf->SetXY(68, $y);

            $text = $this->txt((string) ($value ?: '-'));

            if ($label === 'Alamat' || strlen($text) > 48) {
                $pdf->MultiCell(76, 6, $text);
                $y = $pdf->GetY() + 2;
            } else {
                $pdf->Cell(76, 7, $text, 0, 1, 'L');
                $y += 8;
            }
        }

        $y = max($y + 8, 151);

        $pdf->SetTextColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->SetFont('Helvetica', 'B', 15);
        $pdf->SetXY(15, $y);
        $pdf->Cell(180, 8, $this->txt('Data Paspor'), 0, 1, 'L');

        $pdf->SetDrawColor($this->reportSetting->accentR, $this->reportSetting->accentG, $this->reportSetting->accentB);
        $pdf->Line(15, $y + 11, 195, $y + 11);

        $passportRows = [
            'Nomor Paspor' => $participant->passport_number ?: '-',
            'Tanggal Terbit' => $participant->passport_issued_at ? $participant->passport_issued_at->format('d M Y') : '-',
            'Tanggal Expired' => $participant->passport_expired_at ? $participant->passport_expired_at->format('d M Y') : '-',
        ];

        $y = $this->addRows($pdf, $passportRows, $y + 23);

        $y += 8;

        $pdf->SetTextColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->SetFont('Helvetica', 'B', 15);
        $pdf->SetXY(15, $y);
        $pdf->Cell(180, 8, $this->txt('Kontak Darurat & Catatan'), 0, 1, 'L');

        $pdf->SetDrawColor($this->reportSetting->accentR, $this->reportSetting->accentG, $this->reportSetting->accentB);
        $pdf->Line(15, $y + 11, 195, $y + 11);

        $emergencyRows = [
            'Kontak Darurat' => $participant->emergency_contact_name ?: '-',
            'Nomor Darurat' => $participant->emergency_contact_phone ?: '-',
            'Catatan Kesehatan' => $participant->health_note ?: '-',
            'Catatan Peserta' => $participant->note ?: '-',
        ];

        $y = $this->addRows($pdf, $emergencyRows, $y + 23);

        $y += 8;

        if ($y > 205) {
            $pdf->AddPage('P', 'A4');
            $this->addHeader($pdf, 'CHECKLIST DOKUMEN PESERTA ' . $participant->order_number);
            $y = 58;
        }

        $this->addParticipantDocumentChecklist($pdf, $participant, $y);
        $this->addFooter($pdf);
    }

    private function getParticipantPhotoPath(RegistrationParticipant $participant): ?string
    {
        $photo = $participant->documents
            ->where('document_type', 'pas_foto')
            ->whereNotNull('file_path')
            ->first();

        if (! $photo || ! $photo->file_path) {
            return null;
        }

        if (! Storage::disk('public')->exists($photo->file_path)) {
            return null;
        }

        $fullPath = Storage::disk('public')->path($photo->file_path);
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        if (! in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            return null;
        }

        return $fullPath;
    }

    private function addParticipantPhotoBox(Fpdi $pdf, RegistrationParticipant $participant, float $x, float $y): void
    {
        $boxW = 40;
        $boxH = 60;

        $pdf->SetTextColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetXY($x, $y);
        $pdf->Cell($boxW, 6, $this->txt('Pas Foto 4x6'), 0, 1, 'C');

        $boxX = $x;
        $boxY = $y + 9;

        $pdf->SetFillColor(248, 248, 248);
        $pdf->Rect($boxX, $boxY, $boxW, $boxH, 'F');

        $pdf->SetDrawColor($this->reportSetting->accentR, $this->reportSetting->accentG, $this->reportSetting->accentB);
        $pdf->SetLineWidth(0.4);
        $pdf->Rect($boxX, $boxY, $boxW, $boxH);

        $photoPath = $this->getParticipantPhotoPath($participant);

        if (! $photoPath) {
            $pdf->SetTextColor(150, 150, 150);
            $pdf->SetFont('Helvetica', 'I', 8);
            $pdf->SetXY($boxX + 3, $boxY + 24);
            $pdf->MultiCell($boxW - 6, 5, $this->txt('Pas Foto Belum Ada'), 0, 'C');

            $pdf->SetFont('Helvetica', '', 7);
            $pdf->SetXY($boxX + 3, $boxY + 39);
            $pdf->MultiCell($boxW - 6, 4, $this->txt('Upload dokumen jenis Pas Foto'), 0, 'C');

            return;
        }

        $imageSize = @getimagesize($photoPath);

        if (! $imageSize) {
            $pdf->SetTextColor(150, 150, 150);
            $pdf->SetFont('Helvetica', 'I', 8);
            $pdf->SetXY($boxX + 3, $boxY + 24);
            $pdf->MultiCell($boxW - 6, 5, $this->txt('Foto tidak terbaca'), 0, 'C');

            return;
        }

        [$imageWidthPx, $imageHeightPx] = $imageSize;

        $ratio = max($boxW / $imageWidthPx, $boxH / $imageHeightPx);

        $imageW = $imageWidthPx * $ratio;
        $imageH = $imageHeightPx * $ratio;

        $imageX = $boxX + (($boxW - $imageW) / 2);
        $imageY = $boxY + (($boxH - $imageH) / 2);

        try {
            $pdf->Image($photoPath, $imageX, $imageY, $imageW, $imageH);
        } catch (Throwable $e) {
            $pdf->SetTextColor(150, 150, 150);
            $pdf->SetFont('Helvetica', 'I', 8);
            $pdf->SetXY($boxX + 3, $boxY + 24);
            $pdf->MultiCell($boxW - 6, 5, $this->txt('Foto gagal dimuat'), 0, 'C');
        }

        $pdf->SetDrawColor($this->reportSetting->accentR, $this->reportSetting->accentG, $this->reportSetting->accentB);
        $pdf->SetLineWidth(0.4);
        $pdf->Rect($boxX, $boxY, $boxW, $boxH);
    }

    private function addParticipantDocumentChecklist(
        Fpdi $pdf,
        RegistrationParticipant $participant,
        float $startY
    ): void {
        $pdf->SetTextColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->SetFont('Helvetica', 'B', 15);
        $pdf->SetXY(15, $startY);
        $pdf->Cell(180, 8, $this->txt('Checklist Dokumen Peserta ' . $participant->order_number), 0, 1, 'L');

        $y = $startY + 14;

        $pdf->SetDrawColor(220, 220, 220);
        $pdf->SetFillColor(248, 248, 248);

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->SetXY(15, $y);
        $pdf->Cell(70, 9, $this->txt('Jenis Dokumen'), 1, 0, 'L', true);
        $pdf->Cell(38, 9, $this->txt('Status'), 1, 0, 'L', true);
        $pdf->Cell(82, 9, $this->txt('Catatan'), 1, 1, 'L', true);

        $y += 9;

        $documents = $participant->documents;

        if ($documents->isEmpty()) {
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetTextColor(70, 70, 70);
            $pdf->SetXY(15, $y);
            $pdf->Cell(190, 8, $this->txt('Belum ada dokumen untuk peserta ini.'), 1, 1, 'L');

            return;
        }

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(70, 70, 70);

        foreach ($documents as $document) {
            if ($y > 270) {
                $pdf->AddPage('P', 'A4');
                $this->addHeader($pdf, 'CHECKLIST DOKUMEN PESERTA ' . $participant->order_number);
                $y = 58;
            }

            $pdf->SetXY(15, $y);
            $pdf->Cell(70, 8, $this->txt($this->documentLabel($document->document_type)), 1, 0, 'L');
            $pdf->Cell(38, 8, $this->txt($this->statusLabel($document->status)), 1, 0, 'L');

            $note = $this->txt($document->note ?: '-');
            $note = strlen($note) > 55 ? substr($note, 0, 52) . '...' : $note;

            $pdf->Cell(82, 8, $note, 1, 1, 'L');

            $y += 8;
        }
    }

    private function addDocumentToPdf(
        Fpdi $pdf,
        string $path,
        string $title,
        string $status = '-',
        ?string $note = null
    ): void {
        if (! Storage::disk('public')->exists($path)) {
            $this->addErrorPage($pdf, $title, 'File tidak ditemukan: ' . $path);
            return;
        }

        $fullPath = Storage::disk('public')->path($path);
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        try {
            if ($extension === 'pdf') {
                $pageCount = $pdf->setSourceFile($fullPath);

                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $templateId = $pdf->importPage($pageNo);
                    $size = $pdf->getTemplateSize($templateId);

                    $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';

                    $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                    $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);

                    $this->addSmallStamp($pdf, $title, $status);
                }

                return;
            }

            if (in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
                $this->addImageDocument($pdf, $fullPath, $title, $status, $note);
                return;
            }

            $this->addErrorPage($pdf, $title, 'Format file belum didukung untuk digabung ke PDF: ' . $extension);
        } catch (Throwable $e) {
            $this->addErrorPage(
                $pdf,
                $title,
                'Dokumen tidak bisa digabung otomatis. File mungkin terkunci, rusak, atau versi PDF tidak didukung.'
            );
        }
    }

    private function addImageDocument(
        Fpdi $pdf,
        string $fullPath,
        string $title,
        string $status,
        ?string $note = null
    ): void {
        $imageSize = @getimagesize($fullPath);

        if (! $imageSize) {
            $this->addErrorPage($pdf, $title, 'Gambar tidak bisa dibaca atau file rusak.');
            return;
        }

        [$widthPx, $heightPx] = $imageSize;

        $orientation = $widthPx > $heightPx ? 'L' : 'P';

        $pdf->AddPage($orientation, 'A4');

        $pageWidth = $orientation === 'L' ? 297 : 210;
        $pageHeight = $orientation === 'L' ? 210 : 297;

        $margin = 15;
        $headerHeight = 30;

        $maxWidth = $pageWidth - ($margin * 2);
        $maxHeight = $pageHeight - $headerHeight - 22;

        $ratio = min($maxWidth / $widthPx, $maxHeight / $heightPx);

        $imageWidth = $widthPx * $ratio;
        $imageHeight = $heightPx * $ratio;

        $x = ($pageWidth - $imageWidth) / 2;
        $y = $headerHeight + 5;

        $pdf->SetFillColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->Rect(0, 0, $pageWidth, 24, 'F');

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetXY(12, 8);
        $pdf->Cell($pageWidth - 24, 7, $this->txt($title . ' - ' . $status), 0, 1, 'L');

        if ($note) {
            $pdf->SetTextColor(80, 80, 80);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetXY(15, 26);
            $pdf->MultiCell($pageWidth - 30, 5, $this->txt('Catatan: ' . $note));
            $y += 8;
        }

        $pdf->Image($fullPath, $x, $y, $imageWidth, $imageHeight);
    }

    private function addHeader(Fpdi $pdf, string $title): void
    {
        $pdf->SetFillColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->Rect(0, 0, 210, 44, 'F');

        if ($this->reportSetting->logoPath) {
            try {
                $pdf->Image($this->reportSetting->logoPath, 15, 8, 22, 22);
                $textX = 42;
            } catch (Throwable $e) {
                $textX = 15;
            }
        } else {
            $textX = 15;
        }

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->SetXY($textX, 8);
        $pdf->Cell(153, 8, $this->txt($title), 0, 1, 'L');

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetX($textX);
        $pdf->Cell(153, 6, $this->txt($this->reportSetting->brandName), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetX($textX);
        $pdf->Cell(153, 5, $this->txt($this->reportSetting->tagline), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetX($textX);
        $pdf->Cell(153, 5, $this->txt($this->reportSetting->contactLine()), 0, 1, 'L');

        $pdf->SetDrawColor($this->reportSetting->accentR, $this->reportSetting->accentG, $this->reportSetting->accentB);
        $pdf->SetLineWidth(0.8);
        $pdf->Line(0, 44, 210, 44);
    }

    private function addFooter(Fpdi $pdf): void
    {
        $pdf->SetDrawColor(225, 225, 225);
        $pdf->SetLineWidth(0.2);
        $pdf->Line(15, 277, 195, 277);

        $pdf->SetTextColor(120, 120, 120);
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetXY(15, 280);

        $footer = $this->reportSetting->titleLine();

        if ($this->reportSetting->address && $this->reportSetting->address !== '-') {
            $footer .= ' | ' . $this->reportSetting->address;
        }

        $pdf->Cell(180, 5, $this->txt($footer), 0, 1, 'C');
    }

    private function addRows(Fpdi $pdf, array $rows, float $startY): float
    {
        $y = $startY;

        foreach ($rows as $label => $value) {
            if ($y > 270) {
                $pdf->AddPage('P', 'A4');
                $this->addHeader($pdf, 'LANJUTAN DATA');
                $y = 58;
            }

            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->SetXY(15, $y);
            $pdf->Cell(48, 7, $this->txt($label), 0, 0, 'L');

            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetTextColor(70, 70, 70);
            $pdf->SetXY(68, $y);

            $text = $this->txt((string) ($value ?: '-'));

            if (strlen($text) > 72) {
                $pdf->MultiCell(125, 6, $text);
                $y = $pdf->GetY() + 2;
            } else {
                $pdf->Cell(125, 7, $text, 0, 1, 'L');
                $y += 8;
            }
        }

        return $y;
    }

    private function addSmallStamp(Fpdi $pdf, string $title, string $status): void
    {
        $pdf->SetFillColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 8);

        $pdf->SetXY(8, 8);
        $pdf->Cell(105, 6, $this->txt($title . ' | Status: ' . $status), 0, 0, 'L', true);
    }

    private function addErrorPage(Fpdi $pdf, string $title, string $message): void
    {
        $pdf->AddPage('P', 'A4');

        $this->addHeader($pdf, 'DOKUMEN TIDAK TERBACA');

        $pdf->SetTextColor($this->reportSetting->headerR, $this->reportSetting->headerG, $this->reportSetting->headerB);
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->SetXY(15, 60);
        $pdf->Cell(180, 8, $this->txt($title), 0, 1, 'L');

        $pdf->SetTextColor(180, 30, 30);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetXY(15, 80);
        $pdf->MultiCell(180, 7, $this->txt($message));

        $this->addFooter($pdf);
    }

    private function documentLabel(?string $type): string
    {
        return [
            'ktp' => 'KTP / Identitas',
            'paspor' => 'Paspor',
            'kartu_keluarga' => 'Kartu Keluarga',
            'buku_nikah_akta_ijazah' => 'Buku Nikah / Akta / Ijazah',
            'pas_foto' => 'Pas Foto',
            'sertifikat_vaksin' => 'Sertifikat Vaksin',
            'dokumen_tambahan' => 'Dokumen Tambahan',
        ][$type] ?? 'Dokumen Jamaah';
    }

    private function statusLabel(?string $status): string
    {
        return [
            'belum_dicek' => 'Belum Dicek',
            'valid' => 'Valid',
            'perlu_revisi' => 'Perlu Revisi',
            'ditolak' => 'Ditolak',
        ][$status] ?? '-';
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
        ][$status] ?? ucfirst((string) $status);
    }

    private function txt(?string $text): string
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $text ?? '');
    }
}