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
        'Assalamu’alaikum, saya ingin konsultasi paket haji & umroh Ajmal Noor Wisata.',
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

            {/* HERO SECTION */}
            <section className="relative min-h-[680px] overflow-hidden bg-[#061A35] md:min-h-[760px] xl:min-h-[820px]">
                <img
                    src="/images/hero-kaabah.jpg"
                    alt="Ka'bah"
                    className="absolute inset-0 h-full w-full object-cover opacity-40"
                    onError={(e) => {
                        e.currentTarget.style.display = 'none';
                    }}
                />

                <div className="absolute inset-0 bg-gradient-to-r from-[#061A35]/98 via-[#0B2D5B]/92 to-[#004F41]/60" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(214,168,79,0.28),transparent_55%)]" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_20%_80%,rgba(234,242,255,0.10),transparent_45%)]" />

                <div
                    className="absolute inset-0 opacity-15"
                    style={{
                        backgroundImage:
                            'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.28) 1px, transparent 0)',
                        backgroundSize: '32px 32px',
                    }}
                />

                <div className="relative z-10 mx-auto grid max-w-[1780px] items-center gap-10 px-5 pb-28 pt-10 sm:px-8 md:px-10 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16 lg:pb-32 xl:px-16 xl:pb-36">
                    <div>
                        <div className="mb-6 inline-flex items-center gap-2.5 rounded-full border border-[#D6A84F]/55 bg-white/8 px-4 py-2.5 text-xs font-black text-[#F3D58B] backdrop-blur sm:text-sm">
                            <span className="flex h-6 w-6 items-center justify-center rounded-full border border-[#D6A84F]/70 text-[10px]">
                                ✦
                            </span>
                            Izin Resmi Kemenag RI · PPIU No. 1234 Tahun 2021
                        </div>

                        <h1
                            style={{ fontFamily: "'Playfair Display',serif" }}
                            className="text-4xl font-black leading-[1.07] tracking-tight text-white sm:text-5xl md:text-6xl xl:text-7xl"
                        >
                            Wujudkan Ibadah
                            <br />
                            Umroh & Haji yang
                            <br />
                            <span className="bg-gradient-to-r from-[#FFFFFF] via-[#F3D58B] to-[#D6A84F] bg-clip-text text-transparent">
                                Nyaman, Aman,
                            </span>
                            <br />
                            <span className="bg-gradient-to-r from-[#D6A84F] via-[#F3D58B] to-[#FFFFFF] bg-clip-text text-transparent">
                                dan Terpercaya
                            </span>
                        </h1>

                        <p className="mt-6 max-w-xl text-sm leading-8 text-white/84 sm:text-base md:text-lg md:leading-9">
                            Kami menghadirkan pengalaman ibadah terbaik dengan pembimbing berpengalaman,
                            fasilitas pilihan, dan pelayanan yang tulus dari awal hingga kembali ke tanah air.
                        </p>

                        <div className="mt-8 flex flex-wrap gap-3 sm:gap-4">
                            <Link
                                href="/paket-umroh"
                                className="inline-flex items-center gap-2.5 rounded-full bg-gradient-to-r from-[#D6A84F] to-[#F3D58B] px-6 py-3.5 text-sm font-black text-[#0B2D5B] shadow-xl transition hover:scale-105 hover:shadow-2xl sm:px-7 sm:py-4 sm:text-base"
                            >
                                Lihat Paket Umroh
                                <span className="flex h-6 w-6 items-center justify-center rounded-full bg-[#0B2D5B]/15">
                                    →
                                </span>
                            </Link>

                            <a
                                href={`https://wa.me/${whatsapp}?text=${message}`}
                                target="_blank"
                                rel="noreferrer"
                                className="inline-flex items-center gap-2.5 rounded-full border border-white/25 bg-white/10 px-6 py-3.5 text-sm font-black text-white backdrop-blur transition hover:bg-white/18 sm:px-7 sm:py-4 sm:text-base"
                            >
                                <span className="text-[#F3D58B]">●</span>
                                Konsultasi Gratis
                            </a>
                        </div>

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
                                    <p className="mt-0.5 text-[10px] font-semibold text-white/70 sm:text-xs">
                                        {s.label}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </div>

                    <HeroImagePanel setting={setting} brandName={brandName} />
                </div>

                <div className="absolute bottom-0 left-0 right-0 z-10">
                    <svg
                        viewBox="0 0 1440 80"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        className="w-full"
                        preserveAspectRatio="none"
                    >
                        <path d="M0 80V40C240 0 480 0 720 40C960 80 1200 80 1440 40V80H0Z" fill="#FFF8EC" />
                    </svg>
                </div>
            </section>

            {/* TRUST BAR */}
            <section className="bg-white px-5 py-5 shadow-sm sm:py-6 md:px-8">
                <div className="mx-auto max-w-[1600px]">
                    <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        {[
                            { icon: '⚖️', title: 'Legalitas Resmi', sub: 'Izin PPU Kemenag RI' },
                            { icon: '👥', title: 'Jamaah Puas', sub: '10.000+ jamaah' },
                            { icon: '👨‍🏫', title: 'Pembimbing Ahli', sub: 'Berpengalaman & bersertifikat' },
                            { icon: '💰', title: 'Harga Transparan', sub: 'Tanpa biaya tersembunyi' },
                        ].map((t) => (
                            <div
                                key={t.title}
                                className="flex items-center gap-3 rounded-2xl border border-[#E3EAF5] bg-[#F8FBFF] p-4"
                            >
                                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EAF2FF] text-lg">
                                    {t.icon}
                                </span>
                                <div>
                                    <p className="text-sm font-black text-[#0B2D5B]">{t.title}</p>
                                    <p className="text-xs text-[#64748B]">{t.sub}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* FEATURED PACKAGES */}
            <section className="relative overflow-hidden bg-[#FFF8EC] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div
                    className="absolute inset-0 opacity-35"
                    style={{
                        backgroundImage:
                            'radial-gradient(circle at 1px 1px, rgba(11,45,91,0.10) 1px, transparent 0)',
                        backgroundSize: '28px 28px',
                    }}
                />

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

                    <div className="mt-8 text-center md:hidden">
                        <Link
                            href="/paket-umroh"
                            className="inline-flex items-center gap-2 rounded-full border border-[#0B2D5B] px-7 py-3 text-sm font-black text-[#0B2D5B]"
                        >
                            Lihat Semua Paket →
                        </Link>
                    </div>
                </div>
            </section>

            {/* UPCOMING DEPARTURES */}
            <section className="bg-[#061A35] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div className="mx-auto grid max-w-[1600px] items-start gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16 xl:items-center">
                    <div className="text-white">
                        <p className="text-xs font-black uppercase tracking-widest text-[#F3D58B]">JADWAL</p>

                        <h2
                            style={{ fontFamily: "'Playfair Display',serif" }}
                            className="mt-3 text-4xl font-black leading-tight text-white sm:text-5xl md:text-6xl"
                        >
                            Keberangkatan
                            <br />
                            <span className="text-[#D6A84F]">Terdekat</span>
                        </h2>

                        <div className="mt-5 h-1 w-20 rounded-full bg-[#D6A84F]" />

                        <p className="mt-6 max-w-sm text-base leading-8 text-white/78 md:text-lg">
                            Rencanakan ibadah Anda bersama kami di jadwal keberangkatan terdekat. Kursi terbatas.
                        </p>

                        <Link
                            href="/paket-umroh"
                            className="mt-8 inline-flex items-center gap-3 rounded-full bg-gradient-to-r from-[#D6A84F] to-[#F3D58B] px-7 py-4 text-sm font-black text-[#0B2D5B] shadow-lg transition hover:scale-105 sm:text-base"
                        >
                            Lihat Semua Jadwal
                            <span>→</span>
                        </Link>
                    </div>

                    <div className="overflow-hidden rounded-[32px] border-4 border-[#D6A84F]/75 bg-white p-5 shadow-2xl sm:p-6 md:p-7">
                        <div className="space-y-3">
                            {upcomingPackages.length > 0 ? (
                                upcomingPackages.map((item) => (
                                    <Link
                                        key={item.id}
                                        href={`/paket/${item.slug}`}
                                        className="flex items-center gap-4 rounded-2xl border border-[#E3EAF5] p-4 transition hover:bg-[#F7FAFF] sm:p-5"
                                    >
                                        <span
                                            className={`shrink-0 rounded-full px-3 py-1.5 text-xs font-black ${
                                                item.type?.toLowerCase() === 'haji'
                                                    ? 'bg-[#FFF3DF] text-[#A86808]'
                                                    : 'bg-[#EAF2FF] text-[#0B2D5B]'
                                            }`}
                                        >
                                            {item.type?.toUpperCase()}
                                        </span>

                                        <div className="min-w-0 flex-1">
                                            <p className="truncate text-sm font-black text-[#0B2D5B] sm:text-base">
                                                {item.title}
                                            </p>
                                            <p className="mt-0.5 text-xs text-[#64748B] sm:text-sm">
                                                {formatDate(item.departure_date)}
                                            </p>
                                        </div>

                                        <div className="shrink-0 text-right">
                                            <p className="text-sm font-black text-[#B7791F] sm:text-base">
                                                {formatRupiah(item.price)}
                                            </p>
                                        </div>

                                        <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#EAF2FF] text-lg font-black text-[#0B2D5B]">
                                            ›
                                        </span>
                                    </Link>
                                ))
                            ) : (
                                <div className="rounded-2xl border border-dashed border-[#D6A84F]/50 p-8 text-center">
                                    <p className="font-bold text-[#64748B]">Jadwal keberangkatan belum tersedia.</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </section>

            {/* WHY CHOOSE US */}
            <section className="bg-[#FFF8EC] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div className="mx-auto max-w-[1600px]">
                    <div className="mb-12 text-center">
                        <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">KEUNGGULAN KAMI</p>
                        <h2
                            style={{ fontFamily: "'Playfair Display',serif" }}
                            className="mt-3 text-3xl font-black text-[#0B2D5B] sm:text-4xl md:text-5xl"
                        >
                            Kenapa Jamaah Memilih Kami?
                        </h2>
                        <p className="mx-auto mt-4 max-w-2xl text-base leading-8 text-[#64748B] sm:text-lg">
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
                                className="group rounded-3xl border border-[#E3EAF5] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[#D6A84F]/60 hover:shadow-lg"
                            >
                                <span className="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EAF2FF] text-2xl transition-colors group-hover:bg-[#0B2D5B] group-hover:text-[#F3D58B]">
                                    {f.icon}
                                </span>
                                <h3 className="mt-4 text-base font-black text-[#0B2D5B]">{f.title}</h3>
                                <p className="mt-2 text-sm leading-7 text-[#64748B]">{f.desc}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* TESTIMONIALS */}
            <section className="bg-[#F7FAFF] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div className="mx-auto max-w-[1600px]">
                    <div className="mb-12 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                TESTIMONI JAMAAH
                            </p>
                            <h2
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-3xl font-black text-[#0B2D5B] sm:text-4xl md:text-5xl"
                            >
                                Kata Mereka tentang
                                <br />
                                <span className="text-[#D6A84F]">Perjalanan Bersama Kami</span>
                            </h2>
                        </div>

                        <Link href="/testimoni" className="hidden whitespace-nowrap text-base font-black text-[#0B2D5B] sm:inline">
                            Lihat Semua Testimoni →
                        </Link>
                    </div>

                    {testimonials.length > 0 ? (
                        <div className="grid gap-5 sm:grid-cols-2 md:gap-6 xl:grid-cols-3">
                            {testimonials.map((item) => (
                                <div
                                    key={item.id}
                                    className="relative flex flex-col rounded-[28px] border border-[#E3EAF5] bg-white p-6 shadow-sm"
                                >
                                    <div className="absolute right-6 top-6 flex h-11 w-11 items-center justify-center rounded-xl bg-[#0B2D5B] text-xl text-[#F3D58B]">
                                        "
                                    </div>

                                    <div className="text-base text-[#D6A84F]">
                                        {'★'.repeat(Number(item.rating || 5))}
                                    </div>

                                    <p className="mt-4 flex-1 text-sm leading-8 text-[#475569] sm:text-base">
                                        "{item.message}"
                                    </p>

                                    <div className="my-5 h-px w-full bg-[#E3EAF5]" />

                                    <div className="flex items-center gap-3">
                                        {item.photo ? (
                                            <img
                                                src={`/storage/${item.photo}`}
                                                alt={item.name}
                                                className="h-12 w-12 rounded-full border-3 border-[#F3D58B] object-cover"
                                            />
                                        ) : (
                                            <div className="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#F3D58B] bg-[#EAF2FF] text-lg font-black text-[#0B2D5B]">
                                                {item.name.charAt(0)}
                                            </div>
                                        )}

                                        <div>
                                            <p className="text-sm font-black text-[#0B2D5B]">{item.name}</p>
                                            <p className="text-xs text-[#64748B]">{item.package_name || item.city || 'Jamaah'}</p>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <EmptyBox message="Testimoni belum tersedia. Tambahkan testimoni dari admin panel." />
                    )}

                    <div className="mt-8 text-center sm:hidden">
                        <Link
                            href="/testimoni"
                            className="inline-flex items-center gap-2 rounded-full border border-[#0B2D5B] px-7 py-3 text-sm font-black text-[#0B2D5B]"
                        >
                            Lihat Semua Testimoni →
                        </Link>
                    </div>
                </div>
            </section>

            {/* GALLERY */}
            <section className="bg-[#0B2D5B] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div className="mx-auto grid max-w-[1600px] items-start gap-10 lg:grid-cols-[0.78fr_1.22fr] lg:gap-14 xl:items-center">
                    <div className="text-white">
                        <p className="text-xs font-black uppercase tracking-widest text-[#F3D58B]">DOKUMENTASI JAMAAH</p>
                        <h2
                            style={{ fontFamily: "'Playfair Display',serif" }}
                            className="mt-3 text-3xl font-black leading-tight sm:text-4xl md:text-5xl"
                        >
                            Momen Indah Bersama
                            <br />
                            <span className="text-[#D6A84F]">Jamaah Ajmal Travel</span>
                        </h2>
                        <div className="mt-5 h-1 w-16 rounded-full bg-[#D6A84F]" />
                        <p className="mt-5 max-w-xs text-sm leading-8 text-white/75 md:text-base">
                            Lihat dokumentasi manasik, keberangkatan, dan perjalanan ibadah di tanah suci.
                        </p>
                        <Link
                            href="/galeri"
                            className="mt-8 inline-flex items-center gap-2 text-sm font-black text-[#F3D58B] transition hover:text-white"
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
                                        className="group overflow-hidden rounded-2xl border border-white/10 bg-white/8 shadow-lg transition hover:-translate-y-1 hover:border-[#D6A84F]/60 hover:shadow-2xl"
                                    >
                                        <div className="h-52 overflow-hidden bg-[#061A35]/55 sm:h-56">
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
                                            <div className="mb-2 inline-flex rounded-full bg-[#D6A84F]/15 px-3 py-1 text-[10px] font-black uppercase tracking-wide text-[#F3D58B]">
                                                Lihat Detail
                                            </div>

                                            <p className="text-sm font-black text-white">{item.title}</p>

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
                                <p className="font-bold text-white/70">
                                    Galeri belum tersedia. Tambahkan dokumentasi dari admin panel.
                                </p>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* ARTICLES */}
            <section className="relative overflow-hidden bg-[#FFF8EC] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <div
                    className="absolute inset-0 opacity-30"
                    style={{
                        backgroundImage:
                            'radial-gradient(circle at 1px 1px, rgba(11,45,91,0.10) 1px, transparent 0)',
                        backgroundSize: '28px 28px',
                    }}
                />

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
                                    className="group overflow-hidden rounded-[26px] border border-[#E3EAF5] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                                >
                                    <div className="h-52 overflow-hidden bg-[#EAF2FF]">
                                        {item.thumbnail ? (
                                            <img
                                                src={`/storage/${item.thumbnail}`}
                                                alt={item.title}
                                                className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                            />
                                        ) : (
                                            <div className="flex h-full items-center justify-center text-sm text-[#0B2D5B]/40">
                                                Artikel
                                            </div>
                                        )}
                                    </div>

                                    <div className="p-5 sm:p-6">
                                        <span className="rounded-full bg-[#EAF2FF] px-3 py-1.5 text-xs font-black text-[#0B2D5B]">
                                            {item.category || 'Panduan'}
                                        </span>

                                        <h3 className="mt-3 text-base font-black leading-snug text-[#0B2D5B] sm:text-lg">
                                            {item.title}
                                        </h3>

                                        <p className="mt-2 line-clamp-3 text-sm leading-7 text-[#64748B]">
                                            {item.excerpt || 'Baca artikel selengkapnya.'}
                                        </p>

                                        <p className="mt-5 text-sm font-black text-[#0B2D5B]">Baca Selengkapnya →</p>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    ) : (
                        <EmptyBox message="Artikel belum tersedia. Tambahkan artikel dari admin panel." />
                    )}
                </div>
            </section>

            {/* CTA BANNER */}
            <section className="bg-[#FFF8EC] px-5 pb-16 md:px-8 md:pb-24">
                <div className="mx-auto max-w-[1600px]">
                    <div className="relative overflow-hidden rounded-[36px] bg-gradient-to-br from-[#061A35] via-[#0B2D5B] to-[#123F7A] p-8 shadow-2xl sm:p-10 md:p-12 lg:p-14">
                        <div className="pointer-events-none absolute right-0 top-0 h-64 w-64 -translate-y-1/3 translate-x-1/3 rounded-full bg-[#D6A84F]/12 blur-3xl" />
                        <div className="pointer-events-none absolute bottom-0 left-0 h-48 w-48 -translate-x-1/3 translate-y-1/3 rounded-full bg-[#EAF2FF]/8 blur-2xl" />

                        <div className="relative grid items-center gap-8 md:grid-cols-2 lg:gap-12">
                            <div>
                                <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-[#D6A84F]/45 bg-white/8 px-4 py-2 text-xs font-black text-[#F3D58B]">
                                    <span>●</span> Konsultasi Gratis
                                </div>

                                <h2
                                    style={{ fontFamily: "'Playfair Display',serif" }}
                                    className="text-3xl font-black leading-tight text-white sm:text-4xl md:text-5xl"
                                >
                                    Siap Berangkat Umroh atau Haji
                                    <br />
                                    <span className="text-[#D6A84F]">Bersama Keluarga?</span>
                                </h2>

                                <p className="mt-4 max-w-lg text-sm leading-8 text-white/78 sm:text-base">
                                    Konsultasikan kebutuhan perjalanan ibadah Anda sekarang juga. Tim kami siap membantu
                                    pilihkan paket terbaik.
                                </p>
                            </div>

                            <div className="flex flex-col gap-4 lg:items-end">
                                <a
                                    href={`https://wa.me/${whatsapp}?text=${message}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-[#D6A84F] to-[#F3D58B] px-8 py-5 text-base font-black text-[#0B2D5B] shadow-xl transition hover:scale-105 lg:w-auto"
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

function HeroImagePanel({
    setting,
    brandName,
}: {
    setting: any;
    brandName: string;
}) {
    const heroImage = setting?.hero_image
        ? `/storage/${setting.hero_image}`
        : '/images/hero-kaabah.jpg';

    return (
        <div className="relative hidden lg:block">
            <div className="absolute -inset-5 rounded-[44px] bg-[#D6A84F]/20 blur-2xl" />

            <div className="relative overflow-hidden rounded-[34px] border border-white/18 bg-white/10 p-3 shadow-2xl backdrop-blur">
                <div className="relative h-[560px] overflow-hidden rounded-[28px] bg-[#0B2D5B] xl:h-[620px]">
                    <img
                        src={heroImage}
                        alt={`${brandName} Travel Haji dan Umroh`}
                        className="h-full w-full object-cover"
                        onError={(e) => {
                            e.currentTarget.src = '/images/hero-kaabah.jpg';
                        }}
                    />

                    <div className="absolute inset-0 bg-gradient-to-t from-[#061A35]/86 via-[#061A35]/12 to-transparent" />
                    <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_80%_20%,rgba(214,168,79,0.18),transparent_45%)]" />

                    <div className="absolute left-6 right-6 top-6 flex items-center justify-between">
                        <div className="rounded-full border border-white/20 bg-white/12 px-4 py-2 text-xs font-black uppercase tracking-wide text-white backdrop-blur">
                            Travel Haji & Umroh
                        </div>

                        <div className="flex h-11 w-11 items-center justify-center rounded-full border border-[#D6A84F]/60 bg-[#0B2D5B]/80 text-[#F3D58B] backdrop-blur">
                            ✦
                        </div>
                    </div>

                    <div className="absolute bottom-0 left-0 right-0 p-6 xl:p-7">
                        <div className="rounded-[24px] border border-white/16 bg-[#061A35]/84 p-5 text-white shadow-xl backdrop-blur">
                            <p className="text-xs font-black uppercase tracking-widest text-[#F3D58B]">
                                {brandName}
                            </p>

                            <h3
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-2xl font-black leading-tight text-white xl:text-3xl"
                            >
                                Perjalanan Ibadah yang Nyaman, Aman, dan Terpercaya
                            </h3>

                            <div className="mt-4 grid grid-cols-3 gap-3">
                                {[
                                    { label: 'Legal', value: 'Resmi' },
                                    { label: 'Jamaah', value: 'Puas' },
                                    { label: 'Layanan', value: 'Amanah' },
                                ].map((item) => (
                                    <div
                                        key={item.label}
                                        className="rounded-2xl border border-white/10 bg-white/8 p-3 text-center"
                                    >
                                        <p className="text-sm font-black text-[#F3D58B]">{item.value}</p>
                                        <p className="mt-1 text-[10px] font-semibold uppercase tracking-wide text-white/60">
                                            {item.label}
                                        </p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

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
                <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">{eyebrow}</p>
                <h2
                    style={{ fontFamily: "'Playfair Display',serif" }}
                    className="mt-2 text-3xl font-black text-[#0B2D5B] sm:text-4xl md:text-5xl"
                >
                    {title}
                </h2>
                <p className="mt-3 max-w-2xl text-sm leading-7 text-[#64748B] sm:text-base sm:leading-8">
                    {description}
                </p>
            </div>

            {actionLabel && actionHref && (
                <Link
                    href={actionHref}
                    className="hidden whitespace-nowrap text-base font-black text-[#0B2D5B] transition hover:text-[#D6A84F] md:inline"
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
            className="group overflow-hidden rounded-[26px] border border-[#E3EAF5] bg-white shadow-sm transition hover:-translate-y-1 hover:border-[#D6A84F]/55 hover:shadow-xl"
        >
            <div className="relative h-52 overflow-hidden bg-[#EAF2FF] sm:h-56">
                {item.image ? (
                    <img
                        src={`/storage/${item.image}`}
                        alt={item.title}
                        className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full items-center justify-center text-[#0B2D5B]/40">Paket</div>
                )}

                <div
                    className={`absolute left-4 top-4 rounded-full px-3 py-1.5 text-xs font-black shadow ${
                        isHaji ? 'bg-[#FFF3DF] text-[#A86808]' : 'bg-[#EAF2FF] text-[#0B2D5B]'
                    }`}
                >
                    {item.type?.toUpperCase()}
                </div>

                {item.seat && (
                    <div className="absolute right-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-black text-[#B7791F] shadow">
                        {item.seat} Seat
                    </div>
                )}
            </div>

            <div className="p-5 sm:p-6">
                <h3
                    style={{ fontFamily: "'Playfair Display',serif" }}
                    className="text-lg font-black leading-snug text-[#0B2D5B] sm:text-xl"
                >
                    {item.title}
                </h3>

                <p className="mt-1.5 text-xs font-semibold text-[#64748B] sm:text-sm">
                    {item.duration_days || '-'} Hari · {item.airline || 'Maskapai menyesuaikan'}
                </p>

                <div className="mt-4 space-y-2 text-xs text-[#475569] sm:text-sm">
                    <div className="flex items-center gap-2">
                        <span className="w-4 text-center text-[#0B2D5B]">📅</span>
                        <span>{formatDate(item.departure_date)}</span>
                    </div>

                    <div className="flex items-center gap-2">
                        <span className="w-4 text-center text-[#0B2D5B]">🏨</span>
                        <span>Makkah: {item.makkah_hotel || '-'}</span>
                    </div>

                    <div className="flex items-center gap-2">
                        <span className="w-4 text-center text-[#0B2D5B]">🏨</span>
                        <span>Madinah: {item.madinah_hotel || '-'}</span>
                    </div>
                </div>

                <div className="mt-5 flex items-center justify-between gap-3 border-t border-[#E3EAF5] pt-5">
                    <div>
                        <p className="text-[10px] font-bold uppercase tracking-wide text-[#64748B]">Mulai dari</p>
                        <p className="text-xl font-black text-[#B7791F] sm:text-2xl">{formatRupiah(item.price)}</p>
                    </div>

                    <span className="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-[#0B2D5B] px-5 py-2.5 text-xs font-black text-white shadow transition group-hover:bg-[#061A35]">
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
        <div className="rounded-3xl border border-dashed border-[#D6A84F]/50 bg-white p-10 text-center shadow-sm">
            <p className="font-bold text-[#64748B]">{message}</p>
        </div>
    );
}