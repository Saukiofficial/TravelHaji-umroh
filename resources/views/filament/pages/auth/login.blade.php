@php
    use App\Models\Setting;
    use Illuminate\Support\Facades\Storage;

    $setting = Setting::query()->first();

    $brandName = $setting?->report_brand_name ?: ($setting?->website_name ?: 'Ajmal Noor Wisata');
    $tagline   = $setting?->report_brand_tagline ?: 'Travel Haji & Umroh';
    $phone     = $setting?->report_phone ?: ($setting?->whatsapp ?: ($setting?->phone ?: '6281234567890'));

    $logo    = $setting?->report_logo ?: $setting?->logo;
    $logoUrl = $logo && Storage::disk('public')->exists($logo)
        ? Storage::disk('public')->url($logo)
        : asset('images/ajmal-logo.png');

    $backgroundImage = asset('images/admin-login-kaabah.jpg');
    $whatsappUrl     = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone);
@endphp

<x-filament-panels::page.simple>
<style>
/* ===== RESET ===== */
*, *::before, *::after { box-sizing: border-box; }

html, body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    min-height: 100% !important;
    overflow-x: hidden !important;
    background: #003f35 !important;
    font-family: "Inter", ui-sans-serif, system-ui, -apple-system, sans-serif !important;
}

/* ===== FILAMENT PANEL OVERRIDES ===== */
.fi-simple-layout {
    min-height: 100vh !important;
    padding: 0 !important;
    background: transparent !important;
    display: block !important;
}
.fi-simple-main {
    width: 100% !important;
    max-width: none !important;
    padding: 0 !important;
    margin: 0 !important;
    background: transparent !important;
    box-shadow: none !important;
    border-radius: 0 !important;
}
.fi-simple-page,
.fi-simple-main > .fi-simple-page {
    width: 100% !important;
    max-width: none !important;
    padding: 0 !important;
}
/* Hide Filament header, logo, social OAuth buttons */
.fi-simple-header,
.fi-simple-header-heading,
.fi-logo,
.fi-simple-main > header,
.fi-btn-group,
[data-provider],
.fi-social-buttons,
.fi-auth-social-buttons,
.fi-btn[href*="oauth"],
.fi-btn[href*="social"],
.fi-auth-register-link {
    display: none !important;
}

/* ===== PAGE SHELL ===== */
.ajmal-page {
    position: relative;
    width: 100%;
    min-height: 100vh;
    background: #003f35;
    overflow-x: hidden;
}

/* ===== BACKGROUND IMAGE ===== */
.ajmal-bg {
    position: fixed;
    inset: 0;
    z-index: 0;
    pointer-events: none;
}
/* Full-width photo — matches reference: kaabah fills entire left 65% */
.ajmal-bg-img {
    position: absolute;
    top: 0; left: 0;
    width: 68%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    opacity: 0.96;
}
/*
  Overlay:
  - Left 0–60% (photo/hero area): dark green tint so text is always readable
  - Right 60–100%: solid dark green for card area
  - Vertical: top darker, bottom more transparent (vignette feel)
*/
.ajmal-bg-overlay {
    position: absolute;
    inset: 0;
    background:
        /* horizontal: hero area dark, card area solid */
        linear-gradient(90deg,
            rgba(0,40,30,.68)  0%,
            rgba(0,50,38,.72) 38%,
            rgba(0,55,42,.85) 52%,
            rgba(0,63,53,.97) 64%,
            rgba(0,63,53,1)   72%,
            rgba(0,63,53,1)  100%
        ),
        /* vertical vignette: top & bottom darker */
        linear-gradient(180deg,
            rgba(0,0,0,.38) 0%,
            rgba(0,0,0,.08) 40%,
            rgba(0,0,0,.08) 60%,
            rgba(0,0,0,.42) 100%
        );
}

/* ===== DECORATIVE PATTERNS ===== */
.ajmal-page::before,
.ajmal-page::after {
    content: "";
    position: fixed;
    z-index: 1;
    width: 500px; height: 500px;
    opacity: 0.14;
    pointer-events: none;
    background:
        linear-gradient(30deg,  rgba(232,189,98,.55) 12%, transparent 12.5%, transparent 87%, rgba(232,189,98,.55) 87.5%),
        linear-gradient(150deg, rgba(232,189,98,.55) 12%, transparent 12.5%, transparent 87%, rgba(232,189,98,.55) 87.5%);
    background-size: 80px 140px;
}
.ajmal-page::before { right: -170px; top: -140px; }
.ajmal-page::after  { left: -210px; bottom: -220px; }

/* ===== LOGO RIBBON (desktop only) ===== */
.ajmal-ribbon {
    position: fixed;
    top: 0; left: 52px;
    z-index: 10;
    width: 168px; height: 200px;
    padding: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0 0 28px 28px;
    background: linear-gradient(180deg, #003f35 0%, #005746 100%);
    border: 1px solid rgba(232,189,98,.70);
    box-shadow: 0 24px 60px rgba(0,0,0,.30);
}
.ajmal-ribbon img {
    width: 116px; height: 116px;
    object-fit: contain;
    border-radius: 999px;
    filter: drop-shadow(0 12px 24px rgba(0,0,0,.36));
}

/* ===== MAIN GRID ===== */
.ajmal-shell {
    position: relative;
    z-index: 2;
    min-height: 100vh;
    display: grid;
    /* Left column = photo area, right column = card area (fixed 560px) */
    grid-template-columns: 1fr 560px;
    align-items: center;
    gap: 0;
    padding: 60px 60px 60px 0;
}

/* ===== HERO (left) ===== */
.ajmal-hero {
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 80vh;
    /* Push content right so it clears the ribbon (ribbon = left:52px + width:168px = ~220px) */
    padding-top: 110px;
    padding-left: clamp(230px, 20vw, 310px);
    padding-right: 32px;
}
.ajmal-hero-inner {
    max-width: 420px;
}
.ajmal-hero h1 {
    margin: 0;
    /* White text on dark overlay — readable on any background */
    color: #ffffff;
    font-family: Georgia, "Times New Roman", serif;
    font-weight: 700;
    font-size: clamp(34px, 2.9vw, 52px);
    line-height: 1.10;
    letter-spacing: -0.04em;
    text-shadow:
        0 2px 8px rgba(0,0,0,.45),
        0 8px 28px rgba(0,0,0,.35);
}
.ajmal-hero-line {
    width: 60px; height: 3px;
    margin: 20px 0 22px;
    border-radius: 999px;
    background: linear-gradient(90deg, #c68b27, #f5d081);
}
.ajmal-hero p {
    margin: 0;
    max-width: 360px;
    color: rgba(255,255,255,.88);
    font-size: 15px;
    line-height: 1.78;
    font-weight: 500;
}

/* ===== TRUST BADGE ===== */
.ajmal-trust {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 22px 26px;
    border-radius: 18px;
    background: linear-gradient(135deg, rgba(0,63,53,.98), rgba(0,89,71,.96));
    border: 1px solid rgba(232,189,98,.76);
    box-shadow: 0 22px 56px rgba(0,0,0,.30);
    color: #fff;
    width: min(100%, 440px);
}
.ajmal-trust-icon {
    flex: 0 0 auto;
    width: 72px; height: 72px;
    color: #f5d081;
}
.ajmal-trust-icon svg { width: 100%; height: 100%; }
.ajmal-trust h3 {
    margin: 0 0 8px;
    color: #f5d081;
    font-size: 20px;
    font-weight: 900;
}
.ajmal-trust p {
    margin: 3px 0;
    color: rgba(255,255,255,.90);
    font-size: 14px;
    line-height: 1.45;
}

.ajmal-trust-desktop { margin-top: auto; margin-bottom: 2vh; }
.ajmal-trust-mobile  { display: none; }

/* ===== LOGIN CARD ===== */
.ajmal-card-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0 0 0 28px;
}
.ajmal-card {
    width: 100%;
    max-width: 548px;
    padding: 44px 50px 46px;
    border-radius: 32px;
    background:
        radial-gradient(circle at 18% 0%, rgba(232,189,98,.20), transparent 32%),
        linear-gradient(180deg, rgba(255,252,246,.98), rgba(255,247,232,.97));
    border: 1px solid rgba(232,189,98,.48);
    box-shadow: 0 32px 88px rgba(0,0,0,.40), inset 0 1px 0 rgba(255,255,255,.92);
    backdrop-filter: blur(18px);
}

/* Card header */
.ajmal-card-header { text-align: center; }
.ajmal-card-logo {
    width: 108px; height: 108px;
    object-fit: contain;
    margin: 0 auto 20px;
    border-radius: 999px;
    filter: drop-shadow(0 14px 28px rgba(0,0,0,.18));
    display: block;
}
.ajmal-card h2 {
    margin: 0;
    color: #003f35;
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(30px, 2.6vw, 46px);
    line-height: 1.06;
    font-weight: 700;
    letter-spacing: -0.04em;
}
.ajmal-card-tagline {
    margin: 8px 0 0;
    color: #c68b27;
    font-size: 21px;
    font-weight: 600;
    line-height: 1.2;
}
.ajmal-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin: 18px auto 24px;
}
.ajmal-divider span {
    width: 86px; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(198,139,39,.72), transparent);
}
.ajmal-divider b {
    width: 11px; height: 11px;
    display: block;
    transform: rotate(45deg);
    border-radius: 2px;
    background: #c68b27;
}
.ajmal-card h3 {
    margin: 0;
    color: #003f35;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 28px;
    line-height: 1.2;
    font-weight: 700;
    letter-spacing: -0.03em;
}
.ajmal-card-desc {
    margin: 8px auto 24px;
    max-width: 420px;
    color: rgba(0,45,38,.72);
    font-size: 14px;
    line-height: 1.6;
    font-weight: 500;
}

/* ===== FORM FIELDS ===== */
.ajmal-form { margin-top: 12px; }

.ajmal-form .fi-fo-field-wrp { margin-bottom: 18px !important; }

.ajmal-form .fi-fo-field-wrp-label,
.ajmal-form .fi-fo-field-wrp-label span,
.ajmal-form .fi-label,
.ajmal-form label,
.ajmal-form label span {
    color: #003f35 !important;
    font-size: 13px !important;
    font-weight: 800 !important;
    line-height: 1.2 !important;
}

.ajmal-form .fi-input-wrp {
    min-height: 56px !important;
    border-radius: 12px !important;
    background: rgba(255,255,255,.90) !important;
    border: 1px solid rgba(0,63,53,.20) !important;
    box-shadow: 0 12px 26px rgba(0,63,53,.07), inset 0 1px 0 rgba(255,255,255,.88) !important;
    overflow: hidden !important;
}
.ajmal-form .fi-input-wrp:focus-within {
    border-color: rgba(198,139,39,.90) !important;
    box-shadow: 0 0 0 3px rgba(232,189,98,.22), 0 14px 30px rgba(0,63,53,.10) !important;
}
.ajmal-form input:not([type="checkbox"]) {
    min-height: 54px !important;
    height: 54px !important;
    color: #10231f !important;
    background: transparent !important;
    font-size: 15px !important;
    font-weight: 500 !important;
    border: 0 !important;
    box-shadow: none !important;
}
.ajmal-form input:not([type="checkbox"])::placeholder {
    color: rgba(31,41,55,.45) !important;
}
.ajmal-form input[type="checkbox"] {
    width: 18px !important; height: 18px !important;
    min-width: 18px !important; min-height: 18px !important;
    border-radius: 5px !important;
    border: 1px solid rgba(0,63,53,.35) !important;
    color: #003f35 !important;
    background: #fff !important;
}
.ajmal-form .fi-checkbox-input:checked,
.ajmal-form input[type="checkbox"]:checked {
    background-color: #003f35 !important;
    border-color: #003f35 !important;
}
.ajmal-form .fi-fo-checkbox-list-option-label,
.ajmal-form .fi-checkbox-label,
.ajmal-form .fi-fo-field-wrp-helper-text {
    color: rgba(0,45,38,.72) !important;
    font-weight: 600 !important;
    font-size: 14px !important;
}
.ajmal-form a {
    color: #003f35 !important;
    font-weight: 800 !important;
    text-decoration: none !important;
}

/* ===== SUBMIT BUTTON ===== */
.ajmal-btn {
    width: 100%;
    min-height: 62px;
    margin-top: 10px;
    border: 0;
    border-radius: 12px;
    background: linear-gradient(180deg, #f5d081 0%, #c68b27 100%);
    color: #fff;
    font-size: 20px;
    font-weight: 900;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 11px;
    cursor: pointer;
    box-shadow: 0 18px 40px rgba(198,139,39,.30), inset 0 1px 0 rgba(255,255,255,.55);
    transition: transform 180ms ease, box-shadow 180ms ease;
}
.ajmal-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 24px 48px rgba(198,139,39,.40), inset 0 1px 0 rgba(255,255,255,.65);
}
.ajmal-btn svg { width: 26px; height: 26px; }

/* Footer note */
.ajmal-footer-note {
    margin-top: 22px;
    text-align: center;
    color: rgba(0,45,38,.62);
    font-size: 14px;
    font-weight: 500;
}
.ajmal-footer-note strong {
    margin-left: 6px;
    color: #003f35;
    font-weight: 900;
}

/* ===== WHATSAPP FAB ===== */
.ajmal-wa {
    position: fixed;
    right: 32px; bottom: 28px;
    z-index: 20;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 13px 20px;
    border-radius: 999px;
    background: linear-gradient(180deg, #22c55e, #16a34a);
    color: #fff !important;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,.55);
    box-shadow: 0 18px 42px rgba(22,163,74,.38);
    transition: transform 160ms ease;
}
.ajmal-wa:hover { transform: translateY(-2px); }
.ajmal-wa-icon { width: 40px; height: 40px; display: inline-flex; }
.ajmal-wa-icon svg { width: 40px; height: 40px; }
.ajmal-wa b, .ajmal-wa small { display: block; line-height: 1.2; }
.ajmal-wa b    { font-size: 14px; font-weight: 900; }
.ajmal-wa small{ margin-top: 2px; font-size: 12px; opacity: .90; }


/* =====================================================
   RESPONSIVE — tablet / small desktop  ≤ 1200px
===================================================== */
@media (max-width: 1200px) {
    .ajmal-bg { position: absolute; }
    .ajmal-bg-img {
        width: 100%;
        height: 50%;
        top: 0; left: 0;
        object-position: center 30%;
    }
    .ajmal-bg-overlay {
        background: linear-gradient(180deg,
            rgba(0,63,53,.05)  0%,
            rgba(0,63,53,.72) 42%,
            rgba(0,63,53,.99) 100%
        );
    }

    .ajmal-ribbon { display: none; }

    .ajmal-shell {
        grid-template-columns: 1fr;
        min-height: unset;
        padding: 40px 20px 100px;
        gap: 0;
        align-items: start;
    }

    .ajmal-hero {
        min-height: unset;
        padding-top: 44px;
        padding-left: 0;
        padding-right: 0;
        text-align: center;
        align-items: center;
    }
    .ajmal-hero-inner { max-width: 100%; }
    .ajmal-hero h1 {
        color: #ffffff;
        font-size: clamp(32px, 7.5vw, 52px);
        text-shadow: 0 14px 34px rgba(0,0,0,.45);
    }
    .ajmal-hero-line { margin-left: auto; margin-right: auto; }
    .ajmal-hero p {
        margin: 0 auto;
        max-width: 460px;
        color: rgba(255,255,255,.88);
        font-size: 15px;
    }

    .ajmal-trust-desktop { display: none; }
    .ajmal-trust-mobile {
        display: flex;
        margin: 26px auto 0;
        width: min(100%, 540px);
    }

    .ajmal-card-wrap {
        margin-top: 30px;
        padding: 0;
    }
    .ajmal-card {
        max-width: 540px;
        margin: 0 auto;
        padding: 32px 24px 36px;
        border-radius: 26px;
    }

    .ajmal-wa { right: 16px; bottom: 16px; padding: 11px 14px; }
    .ajmal-wa div { display: none; }
}

/* =====================================================
   RESPONSIVE — mobile  ≤ 480px
===================================================== */
@media (max-width: 480px) {
    .ajmal-shell { padding-inline: 14px; padding-bottom: 90px; }

    .ajmal-hero { padding-top: 30px; }
    .ajmal-hero h1 { font-size: clamp(28px, 9vw, 38px); }
    .ajmal-hero p  { font-size: 14px; }

    .ajmal-card { padding: 28px 16px 32px; border-radius: 22px; }
    .ajmal-card-logo { width: 88px; height: 88px; }
    .ajmal-card h2   { font-size: 26px; }
    .ajmal-card-tagline { font-size: 16px; }
    .ajmal-card h3   { font-size: 22px; }
    .ajmal-divider span { width: 58px; }

    .ajmal-btn { min-height: 56px; font-size: 17px; }

    .ajmal-trust { padding: 16px; gap: 12px; }
    .ajmal-trust-icon { width: 52px; height: 52px; }
    .ajmal-trust h3 { font-size: 16px; margin-bottom: 5px; }
    .ajmal-trust p  { font-size: 12px; }
}
</style>

<div class="ajmal-page">

    {{-- ── Background ── --}}
    <div class="ajmal-bg">
        <img src="{{ $backgroundImage }}" alt="Masjidil Haram" class="ajmal-bg-img">
        <div class="ajmal-bg-overlay"></div>
    </div>

    {{-- ── Logo ribbon (desktop) ── --}}
    <div class="ajmal-ribbon">
        <img src="{{ $logoUrl }}" alt="{{ $brandName }}">
    </div>

    {{-- ── Main grid ── --}}
    <div class="ajmal-shell">

        {{-- Left: Hero --}}
        <section class="ajmal-hero">
            <div class="ajmal-hero-inner">
                <h1>Perjalanan Ibadah,<br>Berbekal Keimanan</h1>
                <div class="ajmal-hero-line"></div>
                <p>{{ $brandName }} berkomitmen menghadirkan layanan terbaik untuk perjalanan ibadah Anda.</p>
            </div>

            {{-- Trust — desktop --}}
            <div class="ajmal-trust ajmal-trust-desktop">
                <div class="ajmal-trust-icon">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 3L19 6V11C19 15.4 16.1 19.5 12 21C7.9 19.5 5 15.4 5 11V6L12 3Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8.8 12.2L10.8 14.2L15.4 9.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <h3>Aman & Terpercaya</h3>
                    <p>Izin PPIU No. 1234 Tahun 2021</p>
                    <p>Sertifikasi & terdaftar di Kemenag RI</p>
                </div>
            </div>
        </section>

        {{-- Right: Card --}}
        <section class="ajmal-card-wrap">
            <div class="ajmal-card">
                <div class="ajmal-card-header">
                    <img src="{{ $logoUrl }}" alt="{{ $brandName }}" class="ajmal-card-logo">
                    <h2>{{ $brandName }}</h2>
                    <p class="ajmal-card-tagline">{{ $tagline }}</p>
                    <div class="ajmal-divider"><span></span><b></b><span></span></div>
                    <h3>Masuk ke Akun Anda</h3>
                    <p class="ajmal-card-desc">
                        Kelola paket, jadwal, pendaftaran, pembayaran, dan perjalanan ibadah jamaah.
                    </p>
                </div>

                <form wire:submit="authenticate" class="ajmal-form">
                    {{ $this->form }}
                    <button type="submit" class="ajmal-btn">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M15 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10 17L15 12L10 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>Masuk</span>
                    </button>
                </form>

                <div class="ajmal-footer-note">
                    <span>Login khusus admin</span>
                    <strong>{{ $brandName }}</strong>
                </div>
            </div>

            {{-- Trust — mobile --}}
            <div class="ajmal-trust ajmal-trust-mobile">
                <div class="ajmal-trust-icon">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 3L19 6V11C19 15.4 16.1 19.5 12 21C7.9 19.5 5 15.4 5 11V6L12 3Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8.8 12.2L10.8 14.2L15.4 9.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <h3>Aman & Terpercaya</h3>
                    <p>Izin PPIU No. 1234 Tahun 2021</p>
                    <p>Sertifikasi & terdaftar di Kemenag RI</p>
                </div>
            </div>
        </section>

    </div>{{-- /.ajmal-shell --}}

    {{-- WhatsApp FAB --}}
    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="ajmal-wa">
        <span class="ajmal-wa-icon">
            <svg viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                <path d="M16.04 3C8.87 3 3.03 8.83 3.03 16c0 2.29.6 4.53 1.75 6.5L3 29l6.66-1.75A12.93 12.93 0 0 0 16.04 29C23.2 29 29.03 23.17 29.03 16S23.2 3 16.04 3Zm0 23.78c-2.02 0-3.99-.54-5.72-1.56l-.41-.24-3.95 1.04 1.05-3.85-.27-.43A10.74 10.74 0 0 1 5.25 16c0-5.95 4.84-10.78 10.79-10.78S26.82 10.05 26.82 16s-4.84 10.78-10.78 10.78Zm5.91-8.08c-.32-.16-1.9-.94-2.2-1.05-.3-.11-.52-.16-.74.16-.22.32-.85 1.05-1.04 1.27-.19.22-.38.24-.7.08-.32-.16-1.36-.5-2.6-1.6-.96-.86-1.61-1.92-1.8-2.24-.19-.32-.02-.5.14-.66.15-.14.32-.38.49-.57.16-.19.22-.32.32-.54.11-.22.05-.4-.03-.57-.08-.16-.74-1.78-1.01-2.44-.27-.64-.54-.55-.74-.56h-.63c-.22 0-.57.08-.87.4-.3.32-1.14 1.11-1.14 2.71s1.17 3.15 1.33 3.37c.16.22 2.3 3.51 5.57 4.92.78.34 1.39.54 1.86.69.78.25 1.49.21 2.05.13.63-.09 1.9-.78 2.17-1.53.27-.75.27-1.39.19-1.53-.08-.14-.3-.22-.62-.38Z"/>
            </svg>
        </span>
        <div>
            <b>Butuh bantuan?</b>
            <small>Chat via WhatsApp</small>
        </div>
    </a>

</div>{{-- /.ajmal-page --}}
</x-filament-panels::page.simple>