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

export default function GalleryShow({
    setting,
    gallery,
    relatedGalleries = [],
}: {
    setting: any;
    gallery: Gallery;
    relatedGalleries: Gallery[];
}) {
    const whatsapp = setting?.whatsapp || '6281234567890';
    const message = encodeURIComponent(
        'Assalamu’alaikum, saya ingin konsultasi paket haji & umroh Ajmal Noor Wisata.',
    );

    const isVideo = gallery.type === 'video';

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
            <Head title={gallery.title} />

            {/* HERO */}
            <section className="relative overflow-hidden bg-[#061A35] px-5 pb-20 pt-16 md:px-8 md:pb-24 md:pt-20">
                {gallery.image ? (
                    <img
                        src={`/storage/${gallery.image}`}
                        alt={gallery.title}
                        className="absolute inset-0 h-full w-full object-cover opacity-26"
                    />
                ) : (
                    <img
                        src="/images/mekkah.jpg"
                        alt={gallery.title}
                        className="absolute inset-0 h-full w-full object-cover opacity-22"
                        onError={(e) => {
                            e.currentTarget.style.display = 'none';
                        }}
                    />
                )}

                <div className="absolute inset-0 bg-gradient-to-r from-[#061A35]/98 via-[#0B2D5B]/92 to-[#004F41]/60" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(214,168,79,0.28),transparent_55%)]" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_20%_85%,rgba(234,242,255,0.10),transparent_48%)]" />
                <Pattern className="absolute inset-0 opacity-15" color="rgba(255,255,255,0.25)" />

                <div className="relative mx-auto max-w-[1100px]">
                    <Link
                        href="/galeri"
                        className="mb-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-black text-white backdrop-blur transition hover:bg-white/15"
                    >
                        ← Kembali ke Galeri
                    </Link>

                    <div className="mb-5 inline-flex rounded-full bg-[#EAF2FF] px-4 py-2 text-xs font-black uppercase tracking-wide text-[#0B2D5B] shadow">
                        {isVideo ? 'VIDEO DOKUMENTASI' : 'FOTO DOKUMENTASI'}
                    </div>

                    <h1
                        style={{ fontFamily: "'Playfair Display',serif" }}
                        className="text-4xl font-black leading-tight text-white sm:text-5xl md:text-6xl"
                    >
                        {gallery.title}
                    </h1>

                    <p className="mt-5 text-sm font-bold uppercase tracking-wide text-[#F3D58B]">
                        Dokumentasi · {formatDate(gallery.created_at)}
                    </p>

                    {gallery.description && (
                        <p className="mt-5 max-w-3xl text-base leading-8 text-white/82 sm:text-lg">
                            {gallery.description}
                        </p>
                    )}
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

            {/* CONTENT */}
            <section className="relative overflow-hidden bg-[#FFF8EC] px-5 py-16 md:px-8 md:py-20">
                <Pattern className="absolute inset-0 opacity-30" color="rgba(11,45,91,0.10)" />

                <div className="relative mx-auto grid max-w-[1400px] gap-8 lg:grid-cols-[1fr_360px]">
                    <div className="overflow-hidden rounded-[32px] border border-[#E3EAF5] bg-white shadow-sm">
                        <div className="bg-[#EAF2FF]">
                            {isVideo && gallery.video_url ? (
                                <div className="aspect-video w-full overflow-hidden bg-black">
                                    <iframe
                                        src={convertToEmbedUrl(gallery.video_url)}
                                        title={gallery.title}
                                        className="h-full w-full"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowFullScreen
                                    />
                                </div>
                            ) : gallery.image ? (
                                <img
                                    src={`/storage/${gallery.image}`}
                                    alt={gallery.title}
                                    className="max-h-[760px] w-full object-cover"
                                />
                            ) : (
                                <div className="flex h-[420px] items-center justify-center text-[#0B2D5B]/40">
                                    Dokumentasi
                                </div>
                            )}
                        </div>

                        <div className="p-6 md:p-8">
                            <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                DETAIL DOKUMENTASI
                            </p>

                            <h2
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-3xl font-black text-[#0B2D5B]"
                            >
                                {gallery.title}
                            </h2>

                            <p className="mt-2 text-sm font-semibold text-[#64748B]">
                                {formatDate(gallery.created_at)}
                            </p>

                            <p className="mt-5 whitespace-pre-line text-sm leading-8 text-[#475569] md:text-base">
                                {gallery.description ||
                                    'Dokumentasi perjalanan jamaah Ajmal Noor Wisata.'}
                            </p>
                        </div>
                    </div>

                    <aside className="h-fit space-y-6 lg:sticky lg:top-28">
                        <div className="rounded-[28px] border border-[#E3EAF5] bg-white p-6 shadow-sm">
                            <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                Konsultasi
                            </p>

                            <h2
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-2xl font-black text-[#0B2D5B]"
                            >
                                Ingin Berangkat Bersama Kami?
                            </h2>

                            <p className="mt-3 text-sm leading-7 text-[#64748B]">
                                Hubungi admin untuk mendapatkan informasi paket,
                                jadwal keberangkatan, dan fasilitas terbaru.
                            </p>

                            <a
                                href={`https://wa.me/${whatsapp}?text=${message}`}
                                target="_blank"
                                rel="noreferrer"
                                className="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-[#0B2D5B] px-6 py-4 text-sm font-black text-white shadow-lg transition hover:bg-[#061A35]"
                            >
                                Konsultasi WhatsApp
                            </a>
                        </div>

                        <div className="rounded-[28px] border border-[#D6A84F]/35 bg-gradient-to-br from-[#061A35] via-[#0B2D5B] to-[#123F7A] p-6 text-white shadow-sm">
                            <p className="text-xs font-black uppercase tracking-widest text-[#F3D58B]">
                                Ajmal Noor Wisata
                            </p>

                            <h3
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-2xl font-black"
                            >
                                Travel Haji & Umroh
                            </h3>

                            <p className="mt-3 text-sm leading-7 text-white/75">
                                Perjalanan ibadah yang nyaman, aman, dan penuh berkah.
                            </p>

                            <Link
                                href="/paket-umroh"
                                className="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-[#D6A84F] to-[#F3D58B] px-6 py-4 text-sm font-black text-[#0B2D5B]"
                            >
                                Lihat Paket Umroh
                            </Link>
                        </div>
                    </aside>
                </div>

                {relatedGalleries.length > 0 && (
                    <div className="relative mx-auto mt-14 max-w-[1400px]">
                        <div className="mb-8">
                            <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                DOKUMENTASI LAINNYA
                            </p>

                            <h2
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-3xl font-black text-[#0B2D5B]"
                            >
                                Galeri Terkait
                            </h2>
                        </div>

                        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {relatedGalleries.map((item) => (
                                <Link
                                    key={item.id}
                                    href={`/galeri/${item.id}`}
                                    className="group overflow-hidden rounded-[26px] border border-[#E3EAF5] bg-white shadow-sm transition hover:-translate-y-1 hover:border-[#D6A84F]/55 hover:shadow-xl"
                                >
                                    <div className="h-52 overflow-hidden bg-[#EAF2FF]">
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
                                    </div>

                                    <div className="p-5">
                                        <h3
                                            style={{ fontFamily: "'Playfair Display',serif" }}
                                            className="text-lg font-black text-[#0B2D5B]"
                                        >
                                            {item.title}
                                        </h3>

                                        <p className="mt-3 text-sm font-black text-[#0B2D5B] transition group-hover:text-[#B7791F]">
                                            Lihat Dokumentasi →
                                        </p>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </div>
                )}
            </section>
        </FrontendLayout>
    );
}

function convertToEmbedUrl(url: string) {
    if (url.includes('youtube.com/watch?v=')) {
        return url.replace('watch?v=', 'embed/');
    }

    if (url.includes('youtu.be/')) {
        const videoId = url.split('youtu.be/')[1]?.split('?')[0];

        return `https://www.youtube.com/embed/${videoId}`;
    }

    return url;
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