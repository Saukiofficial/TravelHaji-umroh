<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'website_name',
        'logo',
        'hero_image',
        'phone',
        'whatsapp',
        'email',
        'address',
        'google_maps',
        'instagram',
        'facebook',
        'tiktok',
        'youtube',
        'meta_title',
        'meta_description',

        // Header laporan / cetak PDF
        'report_brand_name',
        'report_brand_tagline',
        'report_address',
        'report_phone',
        'report_email',
        'report_logo',
        'report_header_color',
        'report_accent_color',
    ];

    public static function current(): ?self
    {
        return self::query()->first();
    }

    public function getReportBrandNameValueAttribute(): string
    {
        return $this->report_brand_name ?: ($this->website_name ?: 'Ajmal Noor Wisata');
    }

    public function getReportBrandTaglineValueAttribute(): string
    {
        return $this->report_brand_tagline ?: 'Travel Haji & Umroh';
    }

    public function getReportAddressValueAttribute(): string
    {
        return $this->report_address ?: ($this->address ?: '-');
    }

    public function getReportPhoneValueAttribute(): string
    {
        return $this->report_phone ?: ($this->whatsapp ?: ($this->phone ?: '-'));
    }

    public function getReportEmailValueAttribute(): string
    {
        return $this->report_email ?: ($this->email ?: '-');
    }

    public function getReportLogoValueAttribute(): ?string
    {
        return $this->report_logo ?: $this->logo;
    }

    public function getReportHeaderColorValueAttribute(): string
    {
        return $this->normalizeHexColor($this->report_header_color ?: '004F41');
    }

    public function getReportAccentColorValueAttribute(): string
    {
        return $this->normalizeHexColor($this->report_accent_color ?: 'E8BD62');
    }

    private function normalizeHexColor(?string $color): string
    {
        $color = trim((string) $color);
        $color = ltrim($color, '#');

        if (! preg_match('/^[A-Fa-f0-9]{6}$/', $color)) {
            return '004F41';
        }

        return strtoupper($color);
    }
}