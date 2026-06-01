<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class ReportSetting
{
    public string $brandName;

    public string $tagline;

    public string $address;

    public string $phone;

    public string $email;

    public ?string $logoPath;

    public string $headerColor;

    public string $accentColor;

    public int $headerR;

    public int $headerG;

    public int $headerB;

    public int $accentR;

    public int $accentG;

    public int $accentB;

    public function __construct()
    {
        $setting = Setting::current();

        $this->brandName = $setting?->report_brand_name_value ?? 'Ajmal Noor Wisata';
        $this->tagline = $setting?->report_brand_tagline_value ?? 'Travel Haji & Umroh';
        $this->address = $setting?->report_address_value ?? '-';
        $this->phone = $setting?->report_phone_value ?? '-';
        $this->email = $setting?->report_email_value ?? '-';

        $this->headerColor = $setting?->report_header_color_value ?? '004F41';
        $this->accentColor = $setting?->report_accent_color_value ?? 'E8BD62';

        [$this->headerR, $this->headerG, $this->headerB] = $this->hexToRgb($this->headerColor);
        [$this->accentR, $this->accentG, $this->accentB] = $this->hexToRgb($this->accentColor);

        $logo = $setting?->report_logo_value;

        $this->logoPath = null;

        if ($logo && Storage::disk('public')->exists($logo)) {
            $fullPath = Storage::disk('public')->path($logo);
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

            if (in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
                $this->logoPath = $fullPath;
            }
        }
    }

    public static function make(): self
    {
        return new self();
    }

    public function titleLine(): string
    {
        return $this->brandName . ' - ' . $this->tagline;
    }

    public function contactLine(): string
    {
        $parts = [];

        if ($this->phone && $this->phone !== '-') {
            $parts[] = 'Telp/WA: ' . $this->phone;
        }

        if ($this->email && $this->email !== '-') {
            $parts[] = 'Email: ' . $this->email;
        }

        return count($parts) ? implode(' | ', $parts) : '-';
    }

    private function hexToRgb(string $hex): array
    {
        $hex = trim($hex);
        $hex = ltrim($hex, '#');

        if (! preg_match('/^[A-Fa-f0-9]{6}$/', $hex)) {
            $hex = '004F41';
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}