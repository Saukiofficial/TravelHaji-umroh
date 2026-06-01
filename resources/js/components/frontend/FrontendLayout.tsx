import { Link } from '@inertiajs/react';
import { ReactNode, useState, useEffect } from 'react';

type Setting = {
    website_name?: string | null;
    whatsapp?: string | null;
    phone?: string | null;
    email?: string | null;
    address?: string | null;
    logo?: string | null;
};

export default function FrontendLayout({
    children,
    setting,
}: {
    children: ReactNode;
    setting?: Setting | null;
}) {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 20);
        window.addEventListener('scroll', onScroll);
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    const whatsapp = setting?.whatsapp || '6281234567890';
    const message = encodeURIComponent(
        'Assalamu\u2019alaikum, saya ingin konsultasi paket haji & umroh Ajmal Noor Wisata.',
    );

    const brandName = setting?.website_name || 'Ajmal Noor Wisata';

    const navLinks = [
    { label: 'Beranda', href: '/' },
    { label: 'Paket Umroh', href: '/paket-umroh' },
    { label: 'Paket Haji', href: '/paket-haji' },
    { label: 'Galeri', href: '/galeri' },
    { label: 'Testimoni', href: '/testimoni' },
    { label: 'Artikel', href: '/artikel' },
    { label: 'Kontak', href: '/kontak' },
]

    return (
        <div className="min-h-screen bg-[#f8f4ec] text-slate-900" style={{ fontFamily: "'DM Sans', sans-serif" }}>

            {/* ── TOP ANNOUNCEMENT BAR ── */}
            <div className="hidden bg-[#001f1a] px-6 py-2.5 lg:block">
                <div className="mx-auto flex max-w-[1780px] items-center justify-between">
                    <div className="flex items-center gap-2 text-xs font-medium text-white/55">
                        <span className="text-[#e8bd62]">✦</span>
                        <span>Izin Resmi Kemenag RI &nbsp;·&nbsp; PPIu No. 1234 Tahun 2021</span>
                    </div>
                    <div className="flex items-center gap-6 text-xs font-medium text-white/55">
                        {setting?.phone && (
                            <span className="flex items-center gap-1.5">
                                <span className="text-[#e8bd62]/80">☎</span> {setting.phone}
                            </span>
                        )}
                        {setting?.email && (
                            <span className="flex items-center gap-1.5">
                                <span className="text-[#e8bd62]/80">✉</span> {setting.email}
                            </span>
                        )}
                        <a
                            href={`https://wa.me/${whatsapp}?text=${message}`}
                            target="_blank"
                            rel="noreferrer"
                            className="flex items-center gap-1.5 rounded-full border border-[#e8bd62]/40 px-3.5 py-1 text-[#e8bd62] transition hover:border-[#e8bd62] hover:bg-[#e8bd62]/10"
                        >
                            <span className="text-[10px]">●</span> WhatsApp Admin
                        </a>
                    </div>
                </div>
            </div>

            {/* ── MAIN NAVBAR ── */}
            <header
                className={`sticky top-0 z-50 transition-all duration-300 ${
                    scrolled
                        ? 'bg-[#002920]/98 shadow-[0_8px_40px_rgba(0,0,0,0.35)] backdrop-blur-xl'
                        : 'bg-[#002920] shadow-none'
                }`}
            >
                {/* Gold accent line top */}
                <div className="h-[2px] bg-gradient-to-r from-transparent via-[#e8bd62] to-transparent" />

                <nav className="mx-auto flex max-w-[1780px] items-center justify-between px-5 py-3.5 md:px-8 md:py-4">

                    {/* ── LOGO ── */}
                    <Link href="/" className="flex min-w-0 items-center gap-3.5 group">
                        {setting?.logo ? (
                            <img
                                src={`/storage/${setting.logo}`}
                                alt={brandName}
                                className="h-11 w-auto object-contain md:h-13"
                            />
                        ) : (
                            <div className="relative flex h-11 w-11 shrink-0 items-center justify-center md:h-12 md:w-12">
                                {/* Outer ring */}
                                <div className="absolute inset-0 rounded-full border border-[#e8bd62]/50" />
                                {/* Inner circle */}
                                <div className="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-[#e8bd62] to-[#c68b27] text-xs font-black text-[#002920] md:h-10 md:w-10 md:text-sm">
                                    AN
                                </div>
                            </div>
                        )}

                        <div className="min-w-0">
                            <div
                                className="truncate text-base font-black leading-none text-white md:text-[17px]"
                                style={{ fontFamily: "'Playfair Display', serif", letterSpacing: '0.01em' }}
                            >
                                {brandName}
                            </div>
                            <div className="mt-1 flex items-center gap-1.5">
                                <span className="h-px w-3 bg-[#e8bd62]/60" />
                                <span className="text-[9px] font-bold uppercase tracking-[0.18em] text-[#e8bd62]/80 md:text-[10px]">
                                    Travel Haji & Umroh
                                </span>
                                <span className="h-px w-3 bg-[#e8bd62]/60" />
                            </div>
                        </div>
                    </Link>

                    {/* ── DESKTOP LINKS ── */}
                    <div className="hidden items-center lg:flex">
                        {navLinks.map((nav, i) => (
                            <Link
                                key={nav.href}
                                href={nav.href}
                                className="relative px-4 py-2 text-sm font-medium text-white/70 transition-colors duration-200 hover:text-white xl:px-5 xl:text-[15px]
                                    after:absolute after:bottom-0 after:left-1/2 after:h-[1.5px] after:w-0 after:-translate-x-1/2 after:rounded-full after:bg-[#e8bd62] after:transition-all after:duration-300 hover:after:w-4/5"
                            >
                                {nav.label}
                            </Link>
                        ))}
                    </div>

                    {/* ── CTA + HAMBURGER ── */}
                    <div className="flex items-center gap-3">
                        {/* WhatsApp CTA — desktop */}
                        <a
                            href={`https://wa.me/${whatsapp}?text=${message}`}
                            target="_blank"
                            rel="noreferrer"
                            className="hidden items-center gap-2 rounded-full border border-[#e8bd62]/60 bg-[#e8bd62]/10 px-5 py-2.5 text-sm font-semibold text-[#e8bd62] transition hover:bg-[#e8bd62] hover:text-[#002920] md:inline-flex"
                        >
                            <svg className="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Konsultasi
                        </a>

                        {/* Hamburger — mobile */}
                        <button
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            className="relative flex h-10 w-10 flex-col items-center justify-center rounded-xl border border-white/15 bg-white/5 transition hover:border-[#e8bd62]/50 hover:bg-[#e8bd62]/10 lg:hidden"
                            aria-label="Toggle menu"
                        >
                            <span className={`absolute h-[1.5px] w-5 rounded-full bg-white transition-all duration-300 ${mobileMenuOpen ? 'rotate-45' : '-translate-y-1.5'}`} />
                            <span className={`absolute h-[1.5px] w-5 rounded-full bg-white transition-all duration-300 ${mobileMenuOpen ? 'opacity-0 scale-x-0' : ''}`} />
                            <span className={`absolute h-[1.5px] w-5 rounded-full bg-white transition-all duration-300 ${mobileMenuOpen ? '-rotate-45' : 'translate-y-1.5'}`} />
                        </button>
                    </div>
                </nav>

                {/* Gold bottom line */}
                <div className="h-px bg-gradient-to-r from-transparent via-[#e8bd62]/30 to-transparent" />

                {/* ── MOBILE MENU ── */}
                <div
                    className={`overflow-hidden transition-all duration-300 ease-in-out lg:hidden ${
                        mobileMenuOpen ? 'max-h-[500px] opacity-100' : 'max-h-0 opacity-0'
                    }`}
                >
                    <div className="bg-[#001f1a] px-5 pb-6 pt-3">
                        {/* Mobile links */}
                        <div className="flex flex-col">
                            {navLinks.map((nav) => (
                                <Link
                                    key={nav.href}
                                    href={nav.href}
                                    onClick={() => setMobileMenuOpen(false)}
                                    className="flex items-center justify-between border-b border-white/5 py-3.5 text-base font-medium text-white/75 transition hover:text-[#e8bd62]"
                                >
                                    {nav.label}
                                    <span className="text-xs text-white/25">›</span>
                                </Link>
                            ))}
                        </div>

                        {/* Mobile CTA */}
                        <a
                            href={`https://wa.me/${whatsapp}?text=${message}`}
                            target="_blank"
                            rel="noreferrer"
                            className="mt-5 flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-r from-[#c68b27] to-[#f4d27f] px-6 py-4 text-base font-bold text-[#002920]"
                        >
                            <svg className="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Konsultasi via WhatsApp
                        </a>
                    </div>
                </div>
            </header>

            <main>{children}</main>

            {/* ── FOOTER ── */}
            <footer className="bg-[#002920] text-white">

                {/* Top divider line */}
                <div className="h-[2px] bg-gradient-to-r from-transparent via-[#e8bd62]/50 to-transparent" />

                <div className="border-b border-white/6 px-6 py-16 md:px-10">
                    <div className="mx-auto grid max-w-[1600px] gap-12 md:grid-cols-2 lg:grid-cols-4 xl:gap-16">

                        {/* Brand */}
                        <div className="lg:col-span-1">
                            <div className="flex items-center gap-3">
                                <div className="relative flex h-12 w-12 items-center justify-center">
                                    <div className="absolute inset-0 rounded-full border border-[#e8bd62]/40" />
                                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#e8bd62] to-[#c68b27] text-xs font-black text-[#002920]">AN</div>
                                </div>
                                <div>
                                    <div className="font-black text-white" style={{ fontFamily: "'Playfair Display', serif" }}>{brandName}</div>
                                    <div className="mt-0.5 flex items-center gap-1.5">
                                        <span className="h-px w-3 bg-[#e8bd62]/50" />
                                        <span className="text-[9px] font-bold uppercase tracking-[0.16em] text-[#e8bd62]/70">Travel Haji & Umroh</span>
                                    </div>
                                </div>
                            </div>
                            <p className="mt-5 max-w-xs text-sm leading-7 text-white/50">
                                Perjalanan ibadah yang nyaman, aman, dan penuh berkah. Melayani jamaah dengan amanah sejak 2025.
                            </p>
                            <div className="mt-6 flex gap-2.5">
                                {[
                                    { label: 'IG', icon: '📷' },
                                    { label: 'FB', icon: '📘' },
                                    { label: 'YT', icon: '▶' },
                                    { label: 'TK', icon: '♪' },
                                ].map((s) => (
                                    <a
                                        key={s.label}
                                        href="#"
                                        className="flex h-9 w-9 items-center justify-center rounded-full border border-white/10 bg-white/5 text-xs text-white/50 transition hover:border-[#e8bd62]/60 hover:bg-[#e8bd62]/10 hover:text-[#e8bd62]"
                                        title={s.label}
                                    >
                                        {s.icon}
                                    </a>
                                ))}
                            </div>
                        </div>

                        {/* Menu */}
                        <div>
                            <h4 className="mb-6 text-[10px] font-bold uppercase tracking-[0.2em] text-[#e8bd62]/80">Menu</h4>
                            <div className="flex flex-col gap-3 text-sm text-white/50">
                                {['Beranda', 'Paket Umroh', 'Paket Haji', 'Galeri', 'Testimoni', 'Artikel', 'Kontak'].map((m) => (
                                    <Link
                                        key={m}
                                        href={
                                                m === 'Beranda'
                                                    ? '/'
                                                    : m === 'Paket Umroh'
                                                    ? '/paket-umroh'
                                                    : m === 'Paket Haji'
                                                        ? '/paket-haji'
                                                        : m === 'Galeri'
                                                        ? '/galeri'
                                                        : m === 'Testimoni'
                                                            ? '/testimoni'
                                                            : m === 'Artikel'
                                                            ? '/artikel'
                                                            : '/kontak'
                                            }
                                        className="flex items-center gap-2 transition hover:text-[#e8bd62]"
                                    >
                                        <span className="h-px w-2 bg-[#e8bd62]/30 transition group-hover:w-4" />
                                        {m}
                                    </Link>
                                ))}
                            </div>
                        </div>

                        {/* Paket Populer */}
                        <div>
                            <h4 className="mb-6 text-[10px] font-bold uppercase tracking-[0.2em] text-[#e8bd62]/80">Paket Populer</h4>
                            <div className="flex flex-col gap-3 text-sm text-white/50">
                                {['Umroh Regular', 'Umroh Plus Thaif', 'Umroh Ramadhan', 'Haji Khusus', 'Haji Furoda'].map((p) => (
                                    <Link
                                        key={p}
                                        href="/paket-umroh"
                                        className="flex items-center gap-2 transition hover:text-[#e8bd62]"
                                    >
                                        <span className="h-px w-2 bg-[#e8bd62]/30" />
                                        {p}
                                    </Link>
                                ))}
                            </div>
                        </div>

                        {/* Kontak */}
                        <div>
                            <h4 className="mb-6 text-[10px] font-bold uppercase tracking-[0.2em] text-[#e8bd62]/80">Kontak Kami</h4>
                            <div className="space-y-3.5 text-sm text-white/50">
                                {setting?.address && (
                                    <p className="flex gap-2.5 leading-7">
                                        <span className="mt-1 shrink-0 text-[#e8bd62]/60">📍</span>
                                        {setting.address}
                                    </p>
                                )}
                                {setting?.phone && (
                                    <p className="flex gap-2.5">
                                        <span className="text-[#e8bd62]/60">☎</span> {setting.phone}
                                    </p>
                                )}
                                {setting?.email && (
                                    <p className="flex gap-2.5">
                                        <span className="text-[#e8bd62]/60">✉</span> {setting.email}
                                    </p>
                                )}
                                {setting?.whatsapp && (
                                    <p className="flex gap-2.5">
                                        <span className="text-[#e8bd62]/60">💬</span> {setting.whatsapp}
                                    </p>
                                )}
                            </div>

                            <div className="mt-6 rounded-2xl border border-[#e8bd62]/20 bg-[#e8bd62]/5 p-4">
                                <p className="text-[10px] font-bold uppercase tracking-widest text-[#e8bd62]/80">Izin Resmi Kemenag RI</p>
                                <p className="mt-1 text-xs text-white/40">PPIu No. 1234 Tahun 2021</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Footer bottom */}
                <div className="px-6 py-5 md:px-10">
                    <div className="mx-auto flex max-w-[1600px] flex-col items-center justify-between gap-3 text-xs text-white/30 md:flex-row">
                        <span>© {new Date().getFullYear()} {brandName}. All rights reserved.</span>
                        <span>Crafted with care for jamaah Indonesia</span>
                    </div>
                </div>
            </footer>

            {/* ── FLOATING WA BUTTON ── */}
            <a
                href={`https://wa.me/${whatsapp}?text=${message}`}
                target="_blank"
                rel="noreferrer"
                className="group fixed bottom-6 right-6 z-50 flex items-center gap-2.5 overflow-hidden rounded-full bg-[#002920] px-5 py-3.5 text-sm font-semibold text-[#e8bd62] shadow-[0_8px_30px_rgba(0,0,0,0.4)] ring-1 ring-[#e8bd62]/30 transition-all duration-300 hover:ring-[#e8bd62]/70 hover:shadow-[0_8px_40px_rgba(232,189,98,0.25)]"
            >
                <svg className="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Konsultasi WA
            </a>
        </div>
    );
}