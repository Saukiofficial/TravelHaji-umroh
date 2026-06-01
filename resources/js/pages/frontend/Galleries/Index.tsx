import FrontendLayout from '@/components/frontend/FrontendLayout';
import { Head, Link } from '@inertiajs/react';

type Gallery = {
    id: number;
    title: string;
    type?: string | null;
    image?: string | null;
    video_url?: string | null;
    description?: string | null;
    created_at?: string | null;
};

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type PaginatedGalleries = {
    data: Gallery[];
    links?: PaginationLink[];
    total?: number;
};

export default function GalleryIndex({
    setting,
    galleries,
}: {
    setting: any;
    galleries: PaginatedGalleries;
}) {
    const whatsapp = setting?.whatsapp || '6281234567890';
    const message = encodeURIComponent(
        'Assalamu’alaikum, saya ingin konsultasi paket haji & umroh Ajmal Noor Wisata.',
    );

    const formatDate = (value?: string | null) => {
        if (!value) return '-';

        return new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
        }).format(new Date(value));
    };

    return (
        <FrontendLayout setting={setting}>
            <Head title="Galeri Dokumentasi Jamaah" />

            {/* HERO */}
            <section className="relative overflow-hidden bg-[#061A35] px-5 pb-20 pt-16 md:px-8 md:pb-24 md:pt-20">
                <img
                    src="/images/mekkah.jpg"
                    alt="Galeri Dokumentasi Jamaah"
                    className="absolute inset-0 h-full w-full object-cover opacity-28"
                    onError={(e) => {
                        e.currentTarget.style.display = 'none';
                    }}
                />

                <div className="absolute inset-0 bg-gradient-to-r from-[#061A35]/98 via-[#0B2D5B]/92 to-[#004F41]/60" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(214,168,79,0.28),transparent_55%)]" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_20%_85%,rgba(234,242,255,0.10),transparent_48%)]" />
                <Pattern className="absolute inset-0 opacity-15" color="rgba(255,255,255,0.25)" />

                <div className="relative mx-auto max-w-[1600px]">
                    <div className="grid items-center gap-10 lg:grid-cols-[1fr_0.7fr]">
                        <div>
                            <div className="mb-6 inline-flex items-center gap-2.5 rounded-full border border-[#D6A84F]/55 bg-white/8 px-4 py-2.5 text-xs font-black uppercase tracking-wide text-[#F3D58B] backdrop-blur sm:text-sm">
                                <span className="flex h-6 w-6 items-center justify-center rounded-full border border-[#D6A84F]/70 text-[10px]">
                                    ✦
                                </span>
                                Dokumentasi Jamaah
                            </div>

                            <h1
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="max-w-4xl text-4xl font-black leading-tight text-white sm:text-5xl md:text-6xl"
                            >
                                Galeri Perjalanan
                                <br />
                                <span className="bg-gradient-to-r from-[#FFFFFF] via-[#F3D58B] to-[#D6A84F] bg-clip-text text-transparent">
                                    Jamaah Ajmal Noor Wisata
                                </span>
                            </h1>

                            <p className="mt-6 max-w-2xl text-base leading-8 text-white/82 sm:text-lg">
                                Lihat dokumentasi kegiatan manasik, keberangkatan, momen jamaah,
                                dan perjalanan ibadah di tanah suci bersama Ajmal Noor Wisata.
                            </p>

                            <div className="mt-8 flex flex-wrap gap-4">
                                <a
                                    href={`https://wa.me/${whatsapp}?text=${message}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex items-center gap-2.5 rounded-full bg-gradient-to-r from-[#D6A84F] to-[#F3D58B] px-7 py-4 text-sm font-black text-[#0B2D5B] shadow-xl transition hover:scale-105 sm:text-base"
                                >
                                    Konsultasi WhatsApp
                                    <span>→</span>
                                </a>

                                <Link
                                    href="/paket-umroh"
                                    className="inline-flex items-center gap-2.5 rounded-full border border-white/25 bg-white/10 px-7 py-4 text-sm font-black text-white backdrop-blur transition hover:bg-white/18 sm:text-base"
                                >
                                    Lihat Paket Umroh
                                </Link>
                            </div>
                        </div>

                        <div className="rounded-[32px] border border-white/12 bg-white/8 p-5 shadow-2xl backdrop-blur">
                            <div className="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                                <HeroInfo icon="📸" title="Dokumentasi" desc="Kegiatan jamaah dan perjalanan" />
                                <HeroInfo icon="🕋" title="Tanah Suci" desc="Momen ibadah di Makkah dan Madinah" />
                                <HeroInfo icon="👥" title="Jamaah" desc="Cerita perjalanan penuh berkah" />
                            </div>
                        </div>
                    </div>
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

            {/* GALLERY LIST */}
            <section className="relative overflow-hidden bg-[#FFF8EC] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <Pattern className="absolute inset-0 opacity-30" color="rgba(11,45,91,0.10)" />

                <div className="relative mx-auto max-w-[1600px]">
                    <div className="mb-10 flex flex-col justify-between gap-5 md:flex-row md:items-end">
                        <div>
                            <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                GALERI DOKUMENTASI
                            </p>

                            <h2
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-3xl font-black text-[#0B2D5B] sm:text-4xl md:text-5xl"
                            >
                                Dokumentasi Perjalanan Jamaah
                            </h2>

                            <p className="mt-3 max-w-2xl text-sm leading-7 text-[#64748B] sm:text-base sm:leading-8">
                                Kumpulan dokumentasi perjalanan, manasik, keberangkatan,
                                dan momen ibadah jamaah.
                            </p>
                        </div>

                        <div className="rounded-2xl border border-[#E3EAF5] bg-white px-5 py-4 shadow-sm">
                            <p className="text-xs font-bold uppercase tracking-wide text-[#64748B]">
                                Total Galeri
                            </p>
                            <p className="text-2xl font-black text-[#0B2D5B]">
                                {galleries.total ?? galleries.data.length} Dokumentasi
                            </p>
                        </div>
                    </div>

                    {galleries.data.length > 0 ? (
                        <>
                            <div className="grid gap-6 sm:grid-cols-2 md:gap-7 xl:grid-cols-3">
                                {galleries.data.map((item) => (
                                    <GalleryCard
                                        key={item.id}
                                        item={item}
                                        formatDate={formatDate}
                                    />
                                ))}
                            </div>

                            {galleries.links && galleries.links.length > 3 && (
                                <div className="mt-12 flex flex-wrap justify-center gap-2">
                                    {galleries.links.map((link, index) => (
                                        <Link
                                            key={`${link.label}-${index}`}
                                            href={link.url || '#'}
                                            preserveScroll
                                            className={[
                                                'rounded-full border px-4 py-2 text-sm font-black transition',
                                                link.active
                                                    ? 'border-[#0B2D5B] bg-[#0B2D5B] text-white'
                                                    : link.url
                                                      ? 'border-[#E3EAF5] bg-white text-[#0B2D5B] hover:border-[#D6A84F] hover:text-[#B7791F]'
                                                      : 'cursor-not-allowed border-[#E3EAF5] bg-[#F1F5F9] text-[#94A3B8]',
                                            ].join(' ')}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </div>
                            )}
                        </>
                    ) : (
                        <EmptyBox message="Galeri belum tersedia. Silakan tambahkan dokumentasi melalui admin panel." />
                    )}
                </div>
            </section>
        </FrontendLayout>
    );
}

function GalleryCard({
    item,
    formatDate,
}: {
    item: Gallery;
    formatDate: (value?: string | null) => string;
}) {
    const isVideo = item.type === 'video';

    return (
        <Link
            href={`/galeri/${item.id}`}
            className="group overflow-hidden rounded-[26px] border border-[#E3EAF5] bg-white shadow-sm transition hover:-translate-y-1 hover:border-[#D6A84F]/55 hover:shadow-xl"
        >
            <div className="relative h-56 overflow-hidden bg-[#EAF2FF] sm:h-64">
                {item.image ? (
                    <img
                        src={`/storage/${item.image}`}
                        alt={item.title}
                        className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full items-center justify-center text-[#0B2D5B]/40">
                        Galeri
                    </div>
                )}

                <div className="absolute left-4 top-4 rounded-full bg-[#EAF2FF] px-3 py-1.5 text-xs font-black text-[#0B2D5B] shadow">
                    {isVideo ? 'VIDEO' : 'FOTO'}
                </div>

                {isVideo && (
                    <div className="absolute inset-0 flex items-center justify-center">
                        <span className="flex h-16 w-16 items-center justify-center rounded-full bg-white/90 text-2xl text-[#0B2D5B] shadow-xl">
                            ▶
                        </span>
                    </div>
                )}
            </div>

            <div className="p-5 sm:p-6">
                <p className="text-xs font-bold uppercase tracking-wide text-[#64748B]">
                    {formatDate(item.created_at)}
                </p>

                <h3
                    style={{ fontFamily: "'Playfair Display',serif" }}
                    className="mt-3 text-lg font-black leading-snug text-[#0B2D5B] sm:text-xl"
                >
                    {item.title}
                </h3>

                <p className="mt-3 line-clamp-3 text-sm leading-7 text-[#64748B]">
                    {item.description || 'Dokumentasi perjalanan jamaah Ajmal Noor Wisata.'}
                </p>

                <p className="mt-5 text-sm font-black text-[#0B2D5B] transition group-hover:text-[#B7791F]">
                    Lihat Dokumentasi →
                </p>
            </div>
        </Link>
    );
}

function HeroInfo({
    icon,
    title,
    desc,
}: {
    icon: string;
    title: string;
    desc: string;
}) {
    return (
        <div className="rounded-2xl border border-white/12 bg-white/10 p-4 backdrop-blur">
            <div className="flex items-center gap-3">
                <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#D6A84F] text-lg text-[#0B2D5B]">
                    {icon}
                </span>
                <div>
                    <p className="font-black text-white">{title}</p>
                    <p className="mt-1 text-xs leading-5 text-white/70">{desc}</p>
                </div>
            </div>
        </div>
    );
}

function Pattern({
    className = '',
    color = 'rgba(11,45,91,0.10)',
}: {
    className?: string;
    color?: string;
}) {
    return (
        <div
            className={className}
            style={{
                backgroundImage: `radial-gradient(circle at 1px 1px, ${color} 1px, transparent 0)`,
                backgroundSize: '28px 28px',
            }}
        />
    );
}

function EmptyBox({ message }: { message: string }) {
    return (
        <div className="rounded-3xl border border-dashed border-[#D6A84F]/50 bg-white p-10 text-center shadow-sm">
            <p className="font-bold text-[#64748B]">{message}</p>
        </div>
    );
}