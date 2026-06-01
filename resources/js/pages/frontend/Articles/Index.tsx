import FrontendLayout from '@/components/frontend/FrontendLayout';
import { Head, Link } from '@inertiajs/react';

type Article = {
    id: number;
    title: string;
    slug: string;
    category?: string | null;
    excerpt?: string | null;
    thumbnail?: string | null;
    created_at?: string | null;
};

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type PaginatedArticles = {
    data: Article[];
    links?: PaginationLink[];
    current_page?: number;
    last_page?: number;
    total?: number;
};

export default function ArticleIndex({
    setting,
    articles,
}: {
    setting: any;
    articles: PaginatedArticles;
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
            <Head title="Artikel & Panduan Umroh Haji" />

            {/* HERO */}
            <section className="relative overflow-hidden bg-[#061A35] px-5 pb-20 pt-16 md:px-8 md:pb-24 md:pt-20">
                <img
                    src="/images/mekkah.jpg"
                    alt="Artikel Umroh dan Haji"
                    className="absolute inset-0 h-full w-full object-cover opacity-28"
                    onError={(e) => {
                        e.currentTarget.style.display = 'none';
                    }}
                />

                <div className="absolute inset-0 bg-gradient-to-r from-[#061A35]/98 via-[#0B2D5B]/92 to-[#004F41]/62" />
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
                                Artikel & Panduan Ibadah
                            </div>

                            <h1
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="max-w-4xl text-4xl font-black leading-tight text-white sm:text-5xl md:text-6xl"
                            >
                                Panduan Umroh & Haji
                                <br />
                                <span className="bg-gradient-to-r from-[#FFFFFF] via-[#F3D58B] to-[#D6A84F] bg-clip-text text-transparent">
                                    untuk Jamaah Indonesia
                                </span>
                            </h1>

                            <p className="mt-6 max-w-2xl text-base leading-8 text-white/82 sm:text-lg">
                                Baca informasi seputar persiapan ibadah, dokumen perjalanan,
                                tips memilih paket, perlengkapan, manasik, dan panduan penting
                                sebelum berangkat ke tanah suci.
                            </p>

                            <div className="mt-8 flex flex-wrap gap-4">
                                <a
                                    href={`https://wa.me/${whatsapp}?text=${message}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex items-center gap-2.5 rounded-full bg-gradient-to-r from-[#D6A84F] to-[#F3D58B] px-7 py-4 text-sm font-black text-[#0B2D5B] shadow-xl transition hover:scale-105 sm:text-base"
                                >
                                    Konsultasi via WhatsApp
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
                                <HeroInfo icon="📚" title="Panduan Lengkap" desc="Materi persiapan ibadah dan perjalanan" />
                                <HeroInfo icon="🕋" title="Tips Ibadah" desc="Informasi praktis untuk jamaah" />
                                <HeroInfo icon="✅" title="Persiapan Dokumen" desc="Checklist sebelum keberangkatan" />
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

            {/* ARTICLES */}
            <section className="relative overflow-hidden bg-[#FFF8EC] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <Pattern className="absolute inset-0 opacity-30" color="rgba(11,45,91,0.10)" />

                <div className="relative mx-auto max-w-[1600px]">
                    <div className="mb-10 flex flex-col justify-between gap-5 md:flex-row md:items-end">
                        <div>
                            <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                ARTIKEL & PANDUAN
                            </p>

                            <h2
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-3xl font-black text-[#0B2D5B] sm:text-4xl md:text-5xl"
                            >
                                Informasi Terbaru
                            </h2>

                            <p className="mt-3 max-w-2xl text-sm leading-7 text-[#64748B] sm:text-base sm:leading-8">
                                Artikel pilihan untuk membantu calon jamaah memahami persiapan,
                                tata cara, dan tips perjalanan ibadah.
                            </p>
                        </div>

                        <div className="rounded-2xl border border-[#E3EAF5] bg-white px-5 py-4 shadow-sm">
                            <p className="text-xs font-bold uppercase tracking-wide text-[#64748B]">
                                Total Artikel
                            </p>
                            <p className="text-2xl font-black text-[#0B2D5B]">
                                {articles.total ?? articles.data.length} Artikel
                            </p>
                        </div>
                    </div>

                    {articles.data.length > 0 ? (
                        <>
                            <div className="grid gap-6 sm:grid-cols-2 md:gap-7 xl:grid-cols-3">
                                {articles.data.map((item) => (
                                    <ArticleCard
                                        key={item.id}
                                        item={item}
                                        formatDate={formatDate}
                                    />
                                ))}
                            </div>

                            {articles.links && articles.links.length > 3 && (
                                <div className="mt-12 flex flex-wrap justify-center gap-2">
                                    {articles.links.map((link, index) => (
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
                        <EmptyBox message="Artikel belum tersedia. Silakan tambahkan artikel melalui admin panel." />
                    )}
                </div>
            </section>

            {/* CTA */}
            <section className="bg-[#FFF8EC] px-5 pb-16 md:px-8 md:pb-24">
                <div className="mx-auto max-w-[1600px]">
                    <div className="relative overflow-hidden rounded-[36px] bg-gradient-to-br from-[#061A35] via-[#0B2D5B] to-[#123F7A] p-8 shadow-2xl sm:p-10 md:p-12">
                        <div className="absolute right-0 top-0 h-64 w-64 -translate-y-1/3 translate-x-1/3 rounded-full bg-[#D6A84F]/12 blur-3xl" />
                        <div className="absolute bottom-0 left-0 h-48 w-48 -translate-x-1/3 translate-y-1/3 rounded-full bg-[#EAF2FF]/8 blur-2xl" />

                        <div className="relative grid items-center gap-8 md:grid-cols-2">
                            <div>
                                <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-[#D6A84F]/45 bg-white/8 px-4 py-2 text-xs font-black text-[#F3D58B]">
                                    <span>●</span> Konsultasi Gratis
                                </div>

                                <h2
                                    style={{ fontFamily: "'Playfair Display',serif" }}
                                    className="text-3xl font-black leading-tight text-white sm:text-4xl md:text-5xl"
                                >
                                    Masih Bingung Memilih Paket Ibadah?
                                </h2>

                                <p className="mt-4 max-w-lg text-sm leading-8 text-white/78 sm:text-base">
                                    Hubungi admin kami untuk mendapatkan rekomendasi paket terbaik
                                    sesuai kebutuhan dan jadwal keberangkatan Anda.
                                </p>
                            </div>

                            <div className="flex flex-col gap-4 lg:items-end">
                                <a
                                    href={`https://wa.me/${whatsapp}?text=${message}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-[#D6A84F] to-[#F3D58B] px-8 py-5 text-base font-black text-[#0B2D5B] shadow-xl transition hover:scale-105 lg:w-auto"
                                >
                                    Konsultasi WhatsApp
                                </a>

                                <Link
                                    href="/paket-umroh"
                                    className="inline-flex w-full items-center justify-center gap-3 rounded-2xl border-2 border-white/30 px-8 py-5 text-base font-black text-white transition hover:border-white/60 lg:w-auto"
                                >
                                    Lihat Paket Umroh
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </FrontendLayout>
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

function ArticleCard({
    item,
    formatDate,
}: {
    item: Article;
    formatDate: (value?: string | null) => string;
}) {
    return (
        <Link
            href={`/artikel/${item.slug}`}
            className="group overflow-hidden rounded-[26px] border border-[#E3EAF5] bg-white shadow-sm transition hover:-translate-y-1 hover:border-[#D6A84F]/55 hover:shadow-xl"
        >
            <div className="relative h-52 overflow-hidden bg-[#EAF2FF] sm:h-56">
                {item.thumbnail ? (
                    <img
                        src={`/storage/${item.thumbnail}`}
                        alt={item.title}
                        className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full items-center justify-center text-[#0B2D5B]/40">
                        Artikel
                    </div>
                )}

                <div className="absolute left-4 top-4 rounded-full bg-[#EAF2FF] px-3 py-1.5 text-xs font-black text-[#0B2D5B] shadow">
                    {item.category || 'Panduan'}
                </div>
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

                <p className="mt-3 line-clamp-4 text-sm leading-7 text-[#64748B]">
                    {item.excerpt || 'Baca artikel selengkapnya.'}
                </p>

                <p className="mt-5 text-sm font-black text-[#0B2D5B] transition group-hover:text-[#B7791F]">
                    Baca Selengkapnya →
                </p>
            </div>
        </Link>
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