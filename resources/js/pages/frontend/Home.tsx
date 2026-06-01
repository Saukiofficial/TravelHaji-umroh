import FrontendLayout from '@/components/frontend/FrontendLayout';
import { Head, Link } from '@inertiajs/react';

type PackageItem = {
    id: number;
    type: string;
    title: string;
    slug: string;
    price?: number | string | null;
    duration_days?: number | null;
    departure_date?: string | null;
    airline?: string | null;
    makkah_hotel?: string | null;
    madinah_hotel?: string | null;
    seat?: number | null;
    image?: string | null;
};

type Testimonial = {
    id: number;
    name: string;
    city?: string | null;
    package_name?: string | null;
    rating: number;
    message: string;
    photo?: string | null;
};

type Gallery = {
    id: number;
    title: string;
    image?: string | null;
    description?: string | null;
};

type Article = {
    id: number;
    title: string;
    slug: string;
    excerpt?: string | null;
    thumbnail?: string | null;
    category?: string | null;
};

export default function Home({
    setting,
    featuredPackages = [],
    upcomingPackages = [],
    testimonials = [],
    galleries = [],
    articles = [],
}: {
    setting: any;
    featuredPackages: PackageItem[];
    upcomingPackages: PackageItem[];
    testimonials: Testimonial[];
    galleries: Gallery[];
    articles: Article[];
}) {
    const whatsapp = setting?.whatsapp || '6281234567890';
    const message = encodeURIComponent(
        'Assalamu\u2019alaikum, saya ingin konsultasi paket haji & umroh Ajmal Noor Wisata.',
    );

    const brandName = setting?.website_name || 'Ajmal Noor Wisata';

    const formatRupiah = (value?: number | string | null) => {
        if (!value) return 'Hubungi Admin';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }).format(Number(value));
    };

    const formatDate = (value?: string | null) => {
        if (!value) return 'Jadwal menyusul';
        return new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
        }).format(new Date(value));
    };

    return (
        <FrontendLayout setting={setting}>
            <Head title={`${brandName} - Travel Haji & Umroh`} />

            {/* ===== HERO SECTION ===== */}
            <section className="relative min-h-[680px] overflow-hidden bg-[#003f35] md:min-h-[760px] xl:min-h-[820px]">
                {/* Background Image */}
                <img
                    src="/images/mekkah.jpg"
                    alt="Ka'bah"
                    className="absolute inset-0 h-full w-full object-cover opacity-45"
                    onError={(e) => { e.currentTarget.style.display = 'none'; }}
                />

                {/* Gradient Overlays */}
                <div className="absolute inset-0 bg-gradient-to-r from-[#002920]/98 via-[#003f35]/88 to-[#003f35]/50" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(232,189,98,0.22),transparent_55%)]" />

                {/* Pattern */}
                <div className="absolute inset-0 opacity-15" style={{
                    backgroundImage: 'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.25) 1px, transparent 0)',
                    backgroundSize: '32px 32px',
                }} />

                <div className="relative z-10 mx-auto grid max-w-[1780px] items-center gap-10 px-5 pb-28 pt-10 sm:px-8 md:px-10 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16 lg:pb-32 xl:px-16 xl:pb-36">

                    {/* Left — Hero Text */}
                    <div>
                        {/* Badge */}
                        <div className="mb-6 inline-flex items-center gap-2.5 rounded-full border border-[#e8bd62]/50 bg-white/8 px-4 py-2.5 text-xs font-black text-[#f5d889] backdrop-blur sm:text-sm">
                            <span className="flex h-6 w-6 items-center justify-center rounded-full border border-[#e8bd62]/70 text-[10px]">✦</span>
                            Izin Resmi Kemenag RI · PPIu No. 1234 Tahun 2021
                        </div>

                        <h1 style={{fontFamily:"'Playfair Display',serif"}} className="text-4xl font-black leading-[1.07] tracking-tight text-white sm:text-5xl md:text-6xl xl:text-7xl">
                            Wujudkan Ibadah
                            <br />
                            Umroh & Haji yang
                            <br />
                            <span className="bg-gradient-to-r from-[#fff3c1] via-[#e8bd62] to-[#fce9a8] bg-clip-text text-transparent">
                                Nyaman, Aman,
                            </span>
                            <br />
                            <span className="bg-gradient-to-r from-[#e8bd62] to-[#fce9a8] bg-clip-text text-transparent">
                                dan Terpercaya
                            </span>
                        </h1>

                        <p className="mt-6 max-w-xl text-sm leading-8 text-white/85 sm:text-base md:text-lg md:leading-9">
                            Kami menghadirkan pengalaman ibadah terbaik dengan pembimbing berpengalaman, fasilitas pilihan, dan pelayanan yang tulus dari awal hingga kembali ke tanah air.
                        </p>

                        {/* CTA Buttons */}
                        <div className="mt-8 flex flex-wrap gap-3 sm:gap-4">
                            <Link
                                href="/paket-umroh"
                                className="inline-flex items-center gap-2.5 rounded-full bg-gradient-to-r from-[#c68b27] to-[#f4d27f] px-6 py-3.5 text-sm font-black text-[#003f35] shadow-xl transition hover:scale-105 hover:shadow-2xl sm:px-7 sm:py-4 sm:text-base"
                            >
                                Lihat Paket Umroh
                                <span className="flex h-6 w-6 items-center justify-center rounded-full bg-[#003f35]/20">→</span>
                            </Link>

                            <a
                                href={`https://wa.me/${whatsapp}?text=${message}`}
                                target="_blank"
                                rel="noreferrer"
                                className="inline-flex items-center gap-2.5 rounded-full border border-white/25 bg-white/10 px-6 py-3.5 text-sm font-black text-white backdrop-blur transition hover:bg-white/18 sm:px-7 sm:py-4 sm:text-base"
                            >
                                <span className="text-[#e8bd62]">●</span>
                                Konsultasi Gratis
                            </a>
                        </div>

                        {/* Stats Bar */}
                        <div className="mt-9 grid max-w-lg grid-cols-3 gap-3 sm:gap-4">
                            {[
                                { icon: '🛡️', number: 'Legal', label: 'Izin Resmi' },
                                { icon: '👥', number: '10.000+', label: 'Jamaah Puas' },
                                { icon: '⭐', number: '5 Bintang', label: 'Rating Jamaah' },
                            ].map((s) => (
                                <div
                                    key={s.label}
                                    className="rounded-2xl border border-white/12 bg-white/8 p-3 text-center text-white backdrop-blur sm:p-4"
                                >
                                    <div className="text-xl sm:text-2xl">{s.icon}</div>
                                    <p className="mt-1.5 text-xs font-black sm:text-sm">{s.number}</p>
                                    <p className="mt-0.5 text-[10px] font-semibold text-white/70 sm:text-xs">{s.label}</p>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Right — Feature Card */}
                    <div className="relative hidden lg:block">
                        <div className="absolute -inset-4 rounded-[44px] bg-[#e8bd62]/20 blur-2xl" />
                        <div className="relative overflow-hidden rounded-[32px] border border-white/15 bg-white/96 p-6 shadow-2xl backdrop-blur xl:p-8">

                            <div className="mb-6 flex items-start gap-4">
                                <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#004f41] to-[#006b58] text-2xl text-[#e8bd62] shadow-lg">
                                    ✦
                                </div>
                                <div>
                                    <h3 style={{fontFamily:"'Playfair Display',serif"}} className="text-xl font-black leading-tight text-[#003f35] xl:text-2xl">
                                        Keunggulan Layanan Kami
                                    </h3>
                                    <p className="mt-1.5 text-sm leading-6 text-slate-500">
                                        Kami memastikan setiap jamaah mendapatkan perjalanan terbaik.
                                    </p>
                                </div>
                            </div>

                            <div className="space-y-2.5">
                                {[
                                    { icon: '⚖️', text: 'Legalitas resmi & terpercaya' },
                                    { icon: '👨‍🏫', text: 'Pembimbing ibadah berpengalaman' },
                                    { icon: '🏨', text: 'Hotel nyaman & strategis' },
                                    { icon: '✈️', text: 'Maskapai terbaik & terjadwal' },
                                    { icon: '💰', text: 'Harga transparan tanpa hidden cost' },
                                    { icon: '💬', text: 'Konsultasi cepat melalui WhatsApp' },
                                ].map((item) => (
                                    <div
                                        key={item.text}
                                        className="flex items-center gap-3 rounded-xl border border-[#e6f3ef] bg-[#f4fbf8] px-4 py-3"
                                    >
                                        <span className="text-base">{item.icon}</span>
                                        <p className="text-sm font-semibold text-[#003f35]">{item.text}</p>
                                        <span className="ml-auto flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#004f41] text-[10px] font-black text-white">✓</span>
                                    </div>
                                ))}
                            </div>

                            <div className="mt-6 rounded-2xl bg-gradient-to-r from-[#003f35] to-[#005a49] p-4 text-center">
                                <p className="text-xs font-bold text-[#e8bd62]">Izin Resmi Kemenag RI</p>
                                <p className="text-sm font-black text-white">PPIu No. 1234 Tahun 2021</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Wave bottom */}
                <div className="absolute bottom-0 left-0 right-0 z-10">
                    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" className="w-full" preserveAspectRatio="none">
                        <path d="M0 80V40C240 0 480 0 720 40C960 80 1200 80 1440 40V80H0Z" fill="#f8f4ec"/>
                    </svg>
                </div>
            </section>

            {/* ===== TRUST BAR ===== */}
            <section className="bg-white px-5 py-5 shadow-sm sm:py-6 md:px-8">
                <div className="mx-auto max-w-[1600px]">
                    <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        {[
                            { icon: '⚖️', title: 'Legalitas Resmi', sub: 'Izin PPU Kemenag RI' },
                            { icon: '👥', title: 'Jamaah Puas', sub: '10.000+ jamaah' },
                            { icon: '👨‍🏫', title: 'Pembimbing Ahli', sub: 'Berpengalaman & bersertifikat' },
                            { icon: '💰', title: 'Harga Transparan', sub: 'Tanpa biaya tersembunyi' },
                        ].map((t) => (
                            <div key={t.title} className="flex items-center gap-3 rounded-2xl border border-[#e8e0d0] bg-[#fafaf7] p-4">
                                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#e6f3ef] text-lg">{t.icon}</span>
                                <div>
                                    <p className="text-sm font-black text-[#003f35]">{t.title}</p>
                                    <p className="text-xs text-slate-500">{t.sub}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* ===== FEATURED PACKAGES ===== */}
            <section className="relative overflow-hidden bg-[#f8f4ec] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div className="absolute inset-0 opacity-30" style={{
                    backgroundImage: 'radial-gradient(circle at 1px 1px, rgba(194,139,41,0.1) 1px, transparent 0)',
                    backgroundSize: '28px 28px',
                }} />

                <div className="relative mx-auto max-w-[1600px]">
                    <SectionHeading
                        eyebrow="PAKET UNGGULAN"
                        title="Paket Umroh & Haji Pilihan"
                        description="Berbagai pilihan paket terbaik dengan fasilitas premium dan harga kompetitif."
                        actionLabel="Lihat Semua Paket →"
                        actionHref="/paket-umroh"
                    />

                    {featuredPackages.length > 0 ? (
                        <div className="grid gap-6 sm:grid-cols-2 md:gap-7 xl:grid-cols-3">
                            {featuredPackages.map((item) => (
                                <PackageCard
                                    key={item.id}
                                    item={item}
                                    formatDate={formatDate}
                                    formatRupiah={formatRupiah}
                                />
                            ))}
                        </div>
                    ) : (
                        <EmptyBox message="Belum ada paket unggulan. Silakan aktifkan paket unggulan dari admin panel." />
                    )}

                    {/* Mobile show all button */}
                    <div className="mt-8 text-center md:hidden">
                        <Link
                            href="/paket-umroh"
                            className="inline-flex items-center gap-2 rounded-full border border-[#004f41] px-7 py-3 text-sm font-black text-[#004f41]"
                        >
                            Lihat Semua Paket →
                        </Link>
                    </div>
                </div>
            </section>

            {/* ===== UPCOMING DEPARTURES ===== */}
            <section className="bg-[#003f35] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div className="mx-auto grid max-w-[1600px] items-start gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16 xl:items-center">

                    {/* Left text */}
                    <div className="text-white">
                        <p className="text-xs font-black uppercase tracking-widest text-[#e8bd62]">JADWAL</p>
                        <h2 style={{fontFamily:"'Playfair Display',serif"}} className="mt-3 text-4xl font-black leading-tight text-white sm:text-5xl md:text-6xl">
                            Keberangkatan
                            <br />
                            <span className="text-[#e8bd62]">Terdekat</span>
                        </h2>

                        <div className="mt-5 h-1 w-20 rounded-full bg-[#e8bd62]" />

                        <p className="mt-6 max-w-sm text-base leading-8 text-white/80 md:text-lg">
                            Rencanakan ibadah Anda bersama kami di jadwal keberangkatan terdekat. Kursi terbatas.
                        </p>

                        <Link
                            href="/paket-umroh"
                            className="mt-8 inline-flex items-center gap-3 rounded-full bg-gradient-to-r from-[#c68b27] to-[#f4d27f] px-7 py-4 text-sm font-black text-[#003f35] shadow-lg transition hover:scale-105 sm:text-base"
                        >
                            Lihat Semua Jadwal
                            <span>→</span>
                        </Link>
                    </div>

                    {/* Right — schedule list */}
                    <div className="overflow-hidden rounded-[32px] border-4 border-[#e8bd62]/70 bg-white p-5 shadow-2xl sm:p-6 md:p-7">
                        <div className="space-y-3">
                            {upcomingPackages.length > 0 ? (
                                upcomingPackages.map((item) => (
                                    <Link
                                        key={item.id}
                                        href={`/paket/${item.slug}`}
                                        className="flex items-center gap-4 rounded-2xl border border-[#e8e0d0] p-4 transition hover:bg-[#f4fbf8] sm:p-5"
                                    >
                                        <span className={`shrink-0 rounded-full px-3 py-1.5 text-xs font-black ${
                                            item.type?.toLowerCase() === 'haji'
                                                ? 'bg-[#fff3e0] text-[#b56a00]'
                                                : 'bg-[#e6f6ef] text-[#004f41]'
                                        }`}>
                                            {item.type?.toUpperCase()}
                                        </span>

                                        <div className="min-w-0 flex-1">
                                            <p className="truncate text-sm font-black text-[#003f35] sm:text-base">{item.title}</p>
                                            <p className="mt-0.5 text-xs text-slate-500 sm:text-sm">
                                                {formatDate(item.departure_date)}
                                            </p>
                                        </div>

                                        <div className="shrink-0 text-right">
                                            <p className="text-sm font-black text-[#b67b1a] sm:text-base">{formatRupiah(item.price)}</p>
                                        </div>

                                        <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#e6f6ef] text-lg font-black text-[#004f41]">›</span>
                                    </Link>
                                ))
                            ) : (
                                <div className="rounded-2xl border border-dashed border-[#d8c8a7] p-8 text-center">
                                    <p className="font-bold text-slate-500">Jadwal keberangkatan belum tersedia.</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </section>

            {/* ===== WHY CHOOSE US ===== */}
            <section className="bg-[#f8f4ec] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div className="mx-auto max-w-[1600px]">
                    <div className="mb-12 text-center">
                        <p className="text-xs font-black uppercase tracking-widest text-[#c68b27]">KEUNGGULAN KAMI</p>
                        <h2 style={{fontFamily:"'Playfair Display',serif"}} className="mt-3 text-3xl font-black text-[#003f35] sm:text-4xl md:text-5xl">
                            Kenapa Jamaah Memilih Kami?
                        </h2>
                        <p className="mx-auto mt-4 max-w-2xl text-base leading-8 text-slate-500 sm:text-lg">
                            Kami berkomitmen memberikan layanan ibadah terbaik yang sesuai dengan kebutuhan setiap jamaah.
                        </p>
                    </div>

                    <div className="grid gap-5 sm:grid-cols-2 md:gap-6 lg:grid-cols-3 xl:grid-cols-5">
                        {[
                            { icon: '⚖️', title: 'Aman & Terpercaya', desc: 'Legalitas resmi dan rekam jejak layanan yang terjamin.' },
                            { icon: '💰', title: 'Harga Transparan', desc: 'Harga jelas sejak awal, tanpa biaya tersembunyi.' },
                            { icon: '💬', title: 'Konsultasi Mudah', desc: 'Tim kami siap membantu melalui WhatsApp kapan saja.' },
                            { icon: '🏨', title: 'Hotel Nyaman', desc: 'Hotel pilihan berbintang dengan lokasi strategis.' },
                            { icon: '👨‍🏫', title: 'Pembimbing Berpengalaman', desc: 'Pembimbing ibadah ahli, sabar dan profesional.' },
                        ].map((f) => (
                            <div
                                key={f.title}
                                className="group rounded-3xl border border-[#e8e0d0] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[#e8bd62]/60 hover:shadow-lg"
                            >
                                <span className="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#e6f3ef] text-2xl group-hover:bg-[#004f41] group-hover:text-[#e8bd62] transition-colors">
                                    {f.icon}
                                </span>
                                <h3 className="mt-4 text-base font-black text-[#003f35]">{f.title}</h3>
                                <p className="mt-2 text-sm leading-7 text-slate-500">{f.desc}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* ===== TESTIMONIALS ===== */}
            <section className="bg-[#fbf7f0] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div className="mx-auto max-w-[1600px]">
                    <div className="mb-12 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <p className="text-xs font-black uppercase tracking-widest text-[#c68b27]">TESTIMONI JAMAAH</p>
                            <h2 style={{fontFamily:"'Playfair Display',serif"}} className="mt-2 text-3xl font-black text-[#003f35] sm:text-4xl md:text-5xl">
                                Kata Mereka tentang
                                <br />
                                <span className="text-[#c68b27]">Perjalanan Bersama Kami</span>
                            </h2>
                        </div>
                        <Link href="/testimoni" className="hidden whitespace-nowrap text-base font-black text-[#004f41] sm:inline">
                            Lihat Semua Testimoni →
                        </Link>
                    </div>

                    {testimonials.length > 0 ? (
                        <div className="grid gap-5 sm:grid-cols-2 md:gap-6 xl:grid-cols-3">
                            {testimonials.map((item) => (
                                <div
                                    key={item.id}
                                    className="relative flex flex-col rounded-[28px] border border-[#e8e0d0] bg-white p-6 shadow-sm"
                                >
                                    {/* Quote icon */}
                                    <div className="absolute right-6 top-6 flex h-11 w-11 items-center justify-center rounded-xl bg-[#004f41] text-xl text-[#e8bd62]">
                                        "
                                    </div>

                                    {/* Stars */}
                                    <div className="text-base text-[#d8a131]">
                                        {'★'.repeat(Number(item.rating || 5))}
                                    </div>

                                    {/* Message */}
                                    <p className="mt-4 flex-1 text-sm leading-8 text-slate-600 sm:text-base">
                                        "{item.message}"
                                    </p>

                                    {/* Divider */}
                                    <div className="my-5 h-px w-full bg-[#eadcc4]" />

                                    {/* Author */}
                                    <div className="flex items-center gap-3">
                                        {item.photo ? (
                                            <img
                                                src={`/storage/${item.photo}`}
                                                alt={item.name}
                                                className="h-12 w-12 rounded-full border-3 border-[#f2e0b3] object-cover"
                                            />
                                        ) : (
                                            <div className="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#f2e0b3] bg-[#e6f6ef] text-lg font-black text-[#004f41]">
                                                {item.name.charAt(0)}
                                            </div>
                                        )}
                                        <div>
                                            <p className="text-sm font-black text-[#003f35]">{item.name}</p>
                                            <p className="text-xs text-slate-400">{item.package_name || item.city || 'Jamaah'}</p>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <EmptyBox message="Testimoni belum tersedia. Tambahkan testimoni dari admin panel." />
                    )}

                    <div className="mt-8 text-center sm:hidden">
                        <Link href="/testimoni" className="inline-flex items-center gap-2 rounded-full border border-[#004f41] px-7 py-3 text-sm font-black text-[#004f41]">
                            Lihat Semua Testimoni →
                        </Link>
                    </div>
                </div>
            </section>

            {/* ===== GALLERY ===== */}
            <section className="bg-[#003f35] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div className="mx-auto grid max-w-[1600px] items-start gap-10 lg:grid-cols-[0.78fr_1.22fr] lg:gap-14 xl:items-center">

                    <div className="text-white">
                        <p className="text-xs font-black uppercase tracking-widest text-[#e8bd62]">DOKUMENTASI JAMAAH</p>
                        <h2 style={{fontFamily:"'Playfair Display',serif"}} className="mt-3 text-3xl font-black leading-tight sm:text-4xl md:text-5xl">
                            Momen Indah Bersama
                            <br />
                            <span className="text-[#e8bd62]">Jamaah Ajmal Travel</span>
                        </h2>
                        <div className="mt-5 h-1 w-16 rounded-full bg-[#e8bd62]" />
                        <p className="mt-5 max-w-xs text-sm leading-8 text-white/75 md:text-base">
                            Lihat dokumentasi manasik, keberangkatan, dan perjalanan ibadah di tanah suci.
                        </p>
                        <Link
                            href="/galeri"
                            className="mt-8 inline-flex items-center gap-2 text-sm font-black text-[#e8bd62] transition hover:text-white"
                        >
                            Lihat Semua Galeri →
                        </Link>
                    </div>

                    <div>
                        {galleries.length > 0 ? (
                        <div className="grid gap-4 sm:grid-cols-3">
                            {galleries.map((item) => (
                                <Link
                                    key={item.id}
                                    href={`/galeri/${item.id}`}
                                    className="group overflow-hidden rounded-2xl border border-white/10 bg-white/8 shadow-lg transition hover:-translate-y-1 hover:border-[#e8bd62]/50 hover:shadow-2xl"
                                >
                                    <div className="h-52 overflow-hidden bg-[#004f41]/40 sm:h-56">
                                        {item.image ? (
                                            <img
                                                src={`/storage/${item.image}`}
                                                alt={item.title}
                                                className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                            />
                                        ) : (
                                            <div className="flex h-full items-center justify-center text-sm text-white/40">
                                                Galeri
                                            </div>
                                        )}
                                    </div>

                                    <div className="p-4">
                                        <div className="mb-2 inline-flex rounded-full bg-[#e8bd62]/15 px-3 py-1 text-[10px] font-black uppercase tracking-wide text-[#e8bd62]">
                                            Lihat Detail
                                        </div>

                                        <p className="text-sm font-black text-white">
                                            {item.title}
                                        </p>

                                        {item.description && (
                                            <p className="mt-1.5 line-clamp-2 text-xs leading-6 text-white/65">
                                                {item.description}
                                            </p>
                                        )}
                                    </div>
                                </Link>
                            ))}
                        </div>
                        ) : (
                            <div className="rounded-3xl border border-white/15 bg-white/8 p-10 text-center">
                                <p className="font-bold text-white/70">Galeri belum tersedia. Tambahkan dokumentasi dari admin panel.</p>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* ===== ARTICLES ===== */}
            <section className="relative overflow-hidden bg-[#f8f4ec] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div className="absolute inset-0 opacity-25" style={{
                    backgroundImage: 'radial-gradient(circle at 1px 1px, rgba(194,139,41,0.1) 1px, transparent 0)',
                    backgroundSize: '28px 28px',
                }} />

                <div className="relative mx-auto max-w-[1600px]">
                    <SectionHeading
                        eyebrow="ARTIKEL & PANDUAN"
                        title="Informasi & Panduan Ibadah"
                        description="Tips, panduan, dan informasi terbaru seputar umroh & haji."
                        actionLabel="Lihat Semua Artikel →"
                        actionHref="/artikel"
                    />

                    {articles.length > 0 ? (
                        <div className="grid gap-6 sm:grid-cols-2 md:gap-7 xl:grid-cols-3">
                            {articles.map((item) => (
                                <Link
                                    key={item.id}
                                    href={`/artikel/${item.slug}`}
                                    className="group overflow-hidden rounded-[26px] border border-[#e8e0d0] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                                >
                                    <div className="h-52 overflow-hidden bg-[#dcefe9]">
                                        {item.thumbnail ? (
                                            <img
                                                src={`/storage/${item.thumbnail}`}
                                                alt={item.title}
                                                className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                            />
                                        ) : (
                                            <div className="flex h-full items-center justify-center text-[#004f41]/40 text-sm">Artikel</div>
                                        )}
                                    </div>

                                    <div className="p-5 sm:p-6">
                                        <span className="rounded-full bg-[#e6f6ef] px-3 py-1.5 text-xs font-black text-[#004f41]">
                                            {item.category || 'Panduan'}
                                        </span>

                                        <h3 className="mt-3 text-base font-black leading-snug text-[#003f35] sm:text-lg">
                                            {item.title}
                                        </h3>

                                        <p className="mt-2 line-clamp-3 text-sm leading-7 text-slate-500">
                                            {item.excerpt || 'Baca artikel selengkapnya.'}
                                        </p>

                                        <p className="mt-5 text-sm font-black text-[#004f41]">
                                            Baca Selengkapnya →
                                        </p>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    ) : (
                        <EmptyBox message="Artikel belum tersedia. Tambahkan artikel dari admin panel." />
                    )}
                </div>
            </section>

            {/* ===== CTA BANNER ===== */}
            <section className="bg-[#f8f4ec] px-5 pb-16 md:px-8 md:pb-24">
                <div className="mx-auto max-w-[1600px]">
                    <div className="overflow-hidden rounded-[36px] bg-gradient-to-br from-[#003f35] via-[#004f41] to-[#005a49] p-8 shadow-2xl sm:p-10 md:p-12 lg:p-14">

                        {/* Decorative */}
                        <div className="absolute right-0 top-0 h-64 w-64 -translate-y-1/3 translate-x-1/3 rounded-full bg-[#e8bd62]/10 blur-3xl pointer-events-none" />
                        <div className="absolute bottom-0 left-0 h-48 w-48 translate-y-1/3 -translate-x-1/3 rounded-full bg-[#e8bd62]/8 blur-2xl pointer-events-none" />

                        <div className="relative grid items-center gap-8 md:grid-cols-2 lg:gap-12">
                            <div>
                                <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-[#e8bd62]/40 bg-white/8 px-4 py-2 text-xs font-black text-[#f5d889]">
                                    <span>●</span> Konsultasi Gratis
                                </div>
                                <h2 style={{fontFamily:"'Playfair Display',serif"}} className="text-3xl font-black leading-tight text-white sm:text-4xl md:text-5xl">
                                    Siap Berangkat Umroh atau Haji
                                    <br />
                                    <span className="text-[#e8bd62]">Bersama Keluarga?</span>
                                </h2>
                                <p className="mt-4 max-w-lg text-sm leading-8 text-white/80 sm:text-base">
                                    Konsultasikan kebutuhan perjalanan ibadah Anda sekarang juga. Tim kami siap membantu pilihkan paket terbaik.
                                </p>
                            </div>

                            <div className="flex flex-col gap-4 lg:items-end">
                                <a
                                    href={`https://wa.me/${whatsapp}?text=${message}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-[#c68b27] to-[#f4d27f] px-8 py-5 text-base font-black text-[#003f35] shadow-xl transition hover:scale-105 lg:w-auto"
                                >
                                    <span className="text-lg">●</span>
                                    Konsultasi via WhatsApp
                                </a>

                                <Link
                                    href="/paket-umroh"
                                    className="inline-flex w-full items-center justify-center gap-3 rounded-2xl border-2 border-white/30 px-8 py-5 text-base font-black text-white transition hover:border-white/60 lg:w-auto"
                                >
                                    Lihat Paket Umroh & Haji
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </FrontendLayout>
    );
}

// ===== HELPER COMPONENTS =====

function SectionHeading({
    eyebrow,
    title,
    description,
    actionLabel,
    actionHref,
}: {
    eyebrow: string;
    title: string;
    description: string;
    actionLabel?: string;
    actionHref?: string;
}) {
    return (
        <div className="mb-10 flex items-end justify-between gap-4">
            <div>
                <p className="text-xs font-black uppercase tracking-widest text-[#c68b27]">{eyebrow}</p>
                <h2 style={{fontFamily:"'Playfair Display',serif"}} className="mt-2 text-3xl font-black text-[#003f35] sm:text-4xl md:text-5xl">
                    {title}
                </h2>
                <p className="mt-3 max-w-2xl text-sm leading-7 text-slate-500 sm:text-base sm:leading-8">
                    {description}
                </p>
            </div>

            {actionLabel && actionHref && (
                <Link
                    href={actionHref}
                    className="hidden whitespace-nowrap text-base font-black text-[#004f41] transition hover:text-[#c68b27] md:inline"
                >
                    {actionLabel}
                </Link>
            )}
        </div>
    );
}

function PackageCard({
    item,
    formatDate,
    formatRupiah,
}: {
    item: PackageItem;
    formatDate: (value?: string | null) => string;
    formatRupiah: (value?: number | string | null) => string;
}) {
    const isHaji = item.type?.toLowerCase() === 'haji';

    return (
        <Link
            href={`/paket/${item.slug}`}
            className="group overflow-hidden rounded-[26px] border border-[#e8e0d0] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl"
        >
            {/* Image */}
            <div className="relative h-52 overflow-hidden bg-[#d5ede5] sm:h-56">
                {item.image ? (
                    <img
                        src={`/storage/${item.image}`}
                        alt={item.title}
                        className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full items-center justify-center text-[#004f41]/40">Paket</div>
                )}

                {/* Badges */}
                <div className={`absolute left-4 top-4 rounded-full px-3 py-1.5 text-xs font-black shadow ${
                    isHaji ? 'bg-[#fff3e0] text-[#b56a00]' : 'bg-[#e6f6ef] text-[#004f41]'
                }`}>
                    {item.type?.toUpperCase()}
                </div>

                {item.seat && (
                    <div className="absolute right-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-black text-[#8e6417] shadow">
                        {item.seat} Seat
                    </div>
                )}
            </div>

            {/* Content */}
            <div className="p-5 sm:p-6">
                <h3 style={{fontFamily:"'Playfair Display',serif"}} className="text-lg font-black leading-snug text-[#003f35] sm:text-xl">
                    {item.title}
                </h3>
                <p className="mt-1.5 text-xs font-semibold text-slate-400 sm:text-sm">
                    {item.duration_days || '-'} Hari · {item.airline || 'Maskapai menyesuaikan'}
                </p>

                <div className="mt-4 space-y-2 text-xs text-slate-600 sm:text-sm">
                    <div className="flex items-center gap-2">
                        <span className="w-4 text-center text-[#004f41]">📅</span>
                        <span>{formatDate(item.departure_date)}</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <span className="w-4 text-center text-[#004f41]">🏨</span>
                        <span>Makkah: {item.makkah_hotel || '-'}</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <span className="w-4 text-center text-[#004f41]">🏨</span>
                        <span>Madinah: {item.madinah_hotel || '-'}</span>
                    </div>
                </div>

                <div className="mt-5 flex items-center justify-between gap-3 border-t border-[#ede8dc] pt-5">
                    <div>
                        <p className="text-[10px] font-bold uppercase tracking-wide text-slate-400">Mulai dari</p>
                        <p className="text-xl font-black text-[#b67b1a] sm:text-2xl">
                            {formatRupiah(item.price)}
                        </p>
                    </div>

                    <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-[#004f41] px-5 py-2.5 text-xs font-black text-white shadow transition group-hover:bg-[#003f35]">
                        Detail Paket
                        <span>›</span>
                    </span>
                </div>
            </div>
        </Link>
    );
}

function EmptyBox({ message }: { message: string }) {
    return (
        <div className="rounded-3xl border border-dashed border-[#d8c8a7] bg-white p-10 text-center shadow-sm">
            <p className="font-bold text-slate-500">{message}</p>
        </div>
    );
}