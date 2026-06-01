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
            <section className="relative overflow-hidden bg-[#003f35] px-5 pb-20 pt-16 md:px-8 md:pb-24 md:pt-20">
                <img
                    src="/images/mekkah.jpg"
                    alt="Artikel Umroh dan Haji"
                    className="absolute inset-0 h-full w-full object-cover opacity-25"
                    onError={(e) => {
                        e.currentTarget.style.display = 'none';
                    }}
                />

                <div className="absolute inset-0 bg-gradient-to-r from-[#002920]/98 via-[#003f35]/92 to-[#003f35]/70" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(232,189,98,0.24),transparent_55%)]" />
                <Pattern className="absolute inset-0 opacity-15" color="rgba(255,255,255,0.25)" />

                <div className="relative mx-auto max-w-[1600px]">
                    <div className="grid items-center gap-10 lg:grid-cols-[1fr_0.7fr]">
                        <div>
                            <div className="mb-6 inline-flex items-center gap-2.5 rounded-full border border-[#e8bd62]/50 bg-white/8 px-4 py-2.5 text-xs font-black uppercase tracking-wide text-[#f5d889] backdrop-blur sm:text-sm">
                                <span className="flex h-6 w-6 items-center justify-center rounded-full border border-[#e8bd62]/70 text-[10px]">
                                    ✦
                                </span>
                                Artikel & Panduan Ibadah
                            </div>

                            <h1 className="max-w-4xl text-4xl font-black leading-tight text-white sm:text-5xl md:text-6xl">
                                Panduan Umroh & Haji
                                <br />
                                <span className="bg-gradient-to-r from-[#fff3c1] via-[#e8bd62] to-[#fce9a8] bg-clip-text text-transparent">
                                    untuk Jamaah Indonesia
                                </span>
                            </h1>

                            <p className="mt-6 max-w-2xl text-base leading-8 text-white/80 sm:text-lg">
                                Baca informasi seputar persiapan ibadah, dokumen perjalanan,
                                tips memilih paket, perlengkapan, manasik, dan panduan penting
                                sebelum berangkat ke tanah suci.
                            </p>

                            <div className="mt-8 flex flex-wrap gap-4">
                                <a
                                    href={`https://wa.me/${whatsapp}?text=${message}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex items-center gap-2.5 rounded-full bg-gradient-to-r from-[#c68b27] to-[#f4d27f] px-7 py-4 text-sm font-black text-[#003f35] shadow-xl transition hover:scale-105 sm:text-base"
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
                        <path d="M0 80V40C240 0 480 0 720 40C960 80 1200 80 1440 40V80H0Z" fill="#f8f4ec" />
                    </svg>
                </div>
            </section>

            {/* ARTICLES */}
            <section className="relative overflow-hidden bg-[#f8f4ec] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <Pattern className="absolute inset-0 opacity-25" color="rgba(194,139,41,0.1)" />

                <div className="relative mx-auto max-w-[1600px]">
                    <div className="mb-10 flex flex-col justify-between gap-5 md:flex-row md:items-end">
                        <div>
                            <p className="text-xs font-black uppercase tracking-widest text-[#c68b27]">
                                ARTIKEL & PANDUAN
                            </p>
                            <h2 className="mt-2 text-3xl font-black text-[#003f35] sm:text-4xl md:text-5xl">
                                Informasi Terbaru
                            </h2>
                            <p className="mt-3 max-w-2xl text-sm leading-7 text-slate-500 sm:text-base sm:leading-8">
                                Artikel pilihan untuk membantu calon jamaah memahami persiapan,
                                tata cara, dan tips perjalanan ibadah.
                            </p>
                        </div>

                        <div className="rounded-2xl border border-[#e8e0d0] bg-white px-5 py-4 shadow-sm">
                            <p className="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Total Artikel
                            </p>
                            <p className="text-2xl font-black text-[#003f35]">
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
                                                    ? 'border-[#004f41] bg-[#004f41] text-white'
                                                    : link.url
                                                      ? 'border-[#e8e0d0] bg-white text-[#003f35] hover:border-[#004f41]'
                                                      : 'cursor-not-allowed border-[#e8e0d0] bg-[#f1ece3] text-slate-400',
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
            <section className="bg-[#f8f4ec] px-5 pb-16 md:px-8 md:pb-24">
                <div className="mx-auto max-w-[1600px]">
                    <div className="relative overflow-hidden rounded-[36px] bg-gradient-to-br from-[#003f35] via-[#004f41] to-[#005a49] p-8 shadow-2xl sm:p-10 md:p-12">
                        <div className="absolute right-0 top-0 h-64 w-64 -translate-y-1/3 translate-x-1/3 rounded-full bg-[#e8bd62]/10 blur-3xl" />

                        <div className="relative grid items-center gap-8 md:grid-cols-2">
                            <div>
                                <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-[#e8bd62]/40 bg-white/8 px-4 py-2 text-xs font-black text-[#f5d889]">
                                    <span>●</span> Konsultasi Gratis
                                </div>

                                <h2 className="text-3xl font-black leading-tight text-white sm:text-4xl md:text-5xl">
                                    Masih Bingung Memilih Paket Ibadah?
                                </h2>

                                <p className="mt-4 max-w-lg text-sm leading-8 text-white/80 sm:text-base">
                                    Hubungi admin kami untuk mendapatkan rekomendasi paket terbaik
                                    sesuai kebutuhan dan jadwal keberangkatan Anda.
                                </p>
                            </div>

                            <div className="flex flex-col gap-4 lg:items-end">
                                <a
                                    href={`https://wa.me/${whatsapp}?text=${message}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-[#c68b27] to-[#f4d27f] px-8 py-5 text-base font-black text-[#003f35] shadow-xl transition hover:scale-105 lg:w-auto"
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
                <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#e8bd62] text-lg">
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
            className="group overflow-hidden rounded-[26px] border border-[#e8e0d0] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl"
        >
            <div className="relative h-52 overflow-hidden bg-[#d5ede5] sm:h-56">
                {item.thumbnail ? (
                    <img
                        src={`/storage/${item.thumbnail}`}
                        alt={item.title}
                        className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full items-center justify-center text-[#004f41]/40">
                        Artikel
                    </div>
                )}

                <div className="absolute left-4 top-4 rounded-full bg-[#e6f6ef] px-3 py-1.5 text-xs font-black text-[#004f41] shadow">
                    {item.category || 'Panduan'}
                </div>
            </div>

            <div className="p-5 sm:p-6">
                <p className="text-xs font-bold uppercase tracking-wide text-slate-400">
                    {formatDate(item.created_at)}
                </p>

                <h3 className="mt-3 text-lg font-black leading-snug text-[#003f35] sm:text-xl">
                    {item.title}
                </h3>

                <p className="mt-3 line-clamp-4 text-sm leading-7 text-slate-500">
                    {item.excerpt || 'Baca artikel selengkapnya.'}
                </p>

                <p className="mt-5 text-sm font-black text-[#004f41]">
                    Baca Selengkapnya →
                </p>
            </div>
        </Link>
    );
}

function Pattern({
    className = '',
    color = 'rgba(194,139,41,0.1)',
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
        <div className="rounded-3xl border border-dashed border-[#d8c8a7] bg-white p-10 text-center shadow-sm">
            <p className="font-bold text-slate-500">{message}</p>
        </div>
    );
}