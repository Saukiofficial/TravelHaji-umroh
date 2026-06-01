import FrontendLayout from '@/components/frontend/FrontendLayout';
import { Head, Link } from '@inertiajs/react';

type Article = {
    title: string;
    category?: string | null;
    excerpt?: string | null;
    content?: string | null;
    thumbnail?: string | null;
    created_at?: string | null;
};

export default function ArticleShow({
    setting,
    article,
}: {
    setting: any;
    article: Article;
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
            <Head title={article.title} />

            {/* HERO */}
            <section className="relative overflow-hidden bg-[#003f35] px-5 pb-20 pt-16 md:px-8 md:pb-24 md:pt-20">
                {article.thumbnail ? (
                    <img
                        src={`/storage/${article.thumbnail}`}
                        alt={article.title}
                        className="absolute inset-0 h-full w-full object-cover opacity-25"
                    />
                ) : (
                    <img
                        src="/images/mekkah.jpg"
                        alt={article.title}
                        className="absolute inset-0 h-full w-full object-cover opacity-20"
                        onError={(e) => {
                            e.currentTarget.style.display = 'none';
                        }}
                    />
                )}

                <div className="absolute inset-0 bg-gradient-to-r from-[#002920]/98 via-[#003f35]/92 to-[#003f35]/70" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(232,189,98,0.24),transparent_55%)]" />
                <Pattern className="absolute inset-0 opacity-15" color="rgba(255,255,255,0.25)" />

                <div className="relative mx-auto max-w-[1100px]">
                    <Link
                        href="/artikel"
                        className="mb-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-black text-white backdrop-blur transition hover:bg-white/15"
                    >
                        ← Kembali ke Artikel
                    </Link>

                    <div className="mb-5 inline-flex rounded-full bg-[#e6f6ef] px-4 py-2 text-xs font-black uppercase tracking-wide text-[#004f41] shadow">
                        {article.category || 'Panduan'}
                    </div>

                    <h1 className="text-4xl font-black leading-tight text-white sm:text-5xl md:text-6xl">
                        {article.title}
                    </h1>

                    <p className="mt-5 text-sm font-bold uppercase tracking-wide text-[#e8bd62]">
                        Dipublikasikan pada {formatDate(article.created_at)}
                    </p>

                    {article.excerpt && (
                        <p className="mt-5 max-w-3xl text-base leading-8 text-white/80 sm:text-lg">
                            {article.excerpt}
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
                        <path d="M0 80V40C240 0 480 0 720 40C960 80 1200 80 1440 40V80H0Z" fill="#f8f4ec" />
                    </svg>
                </div>
            </section>

            {/* CONTENT */}
            <section className="relative overflow-hidden bg-[#f8f4ec] px-5 py-16 md:px-8 md:py-20">
                <Pattern className="absolute inset-0 opacity-25" color="rgba(194,139,41,0.1)" />

                <div className="relative mx-auto grid max-w-[1300px] gap-8 lg:grid-cols-[1fr_360px]">
                    <article className="overflow-hidden rounded-[32px] border border-[#e8e0d0] bg-white shadow-sm">
                        {article.thumbnail && (
                            <div className="h-72 overflow-hidden bg-[#d5ede5] md:h-[460px]">
                                <img
                                    src={`/storage/${article.thumbnail}`}
                                    alt={article.title}
                                    className="h-full w-full object-cover"
                                />
                            </div>
                        )}

                        <div className="p-6 md:p-10">
                            <div
                                className="prose max-w-none prose-headings:text-[#003f35] prose-p:leading-8 prose-p:text-slate-600 prose-a:font-bold prose-a:text-[#004f41] prose-strong:text-[#003f35] prose-ul:text-slate-600 prose-ol:text-slate-600"
                                dangerouslySetInnerHTML={{
                                    __html: article.content || '',
                                }}
                            />
                        </div>
                    </article>

                    <aside className="h-fit space-y-6 lg:sticky lg:top-28">
                        <div className="rounded-[28px] border border-[#e8e0d0] bg-white p-6 shadow-sm">
                            <p className="text-xs font-black uppercase tracking-widest text-[#c68b27]">
                                Butuh Bantuan?
                            </p>
                            <h2 className="mt-2 text-2xl font-black text-[#003f35]">
                                Konsultasi Perjalanan Ibadah
                            </h2>
                            <p className="mt-3 text-sm leading-7 text-slate-500">
                                Diskusikan kebutuhan paket umroh dan haji Anda bersama admin
                                Ajmal Noor Wisata.
                            </p>

                            <a
                                href={`https://wa.me/${whatsapp}?text=${message}`}
                                target="_blank"
                                rel="noreferrer"
                                className="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-[#004f41] px-6 py-4 text-sm font-black text-white shadow-lg transition hover:bg-[#003f35]"
                            >
                                Konsultasi WhatsApp
                            </a>
                        </div>

                        <div className="rounded-[28px] border border-[#e8e0d0] bg-[#003f35] p-6 text-white shadow-sm">
                            <p className="text-xs font-black uppercase tracking-widest text-[#e8bd62]">
                                Ajmal Noor Wisata
                            </p>
                            <h3 className="mt-2 text-2xl font-black">
                                Travel Haji & Umroh
                            </h3>
                            <p className="mt-3 text-sm leading-7 text-white/75">
                                Perjalanan ibadah yang nyaman, aman, dan penuh berkah.
                                Melayani jamaah dengan amanah dan profesional.
                            </p>

                            <Link
                                href="/paket-umroh"
                                className="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-[#c68b27] to-[#f4d27f] px-6 py-4 text-sm font-black text-[#003f35]"
                            >
                                Lihat Paket Umroh
                            </Link>
                        </div>

                        <div className="rounded-[28px] border border-[#e8e0d0] bg-white p-6 shadow-sm">
                            <p className="text-xs font-black uppercase tracking-widest text-[#c68b27]">
                                Kategori
                            </p>
                            <div className="mt-4 flex flex-wrap gap-2">
                                {['Panduan Umroh', 'Persiapan Umroh', 'Tips Haji', 'Dokumen', 'Manasik'].map((item) => (
                                    <span
                                        key={item}
                                        className="rounded-full bg-[#e6f6ef] px-4 py-2 text-xs font-black text-[#004f41]"
                                    >
                                        {item}
                                    </span>
                                ))}
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </FrontendLayout>
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