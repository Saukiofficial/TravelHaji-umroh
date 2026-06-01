import FrontendLayout from '@/components/frontend/FrontendLayout';
import { Head, Link } from '@inertiajs/react';

export default function Contact({ setting }: { setting: any }) {
    const whatsapp = setting?.whatsapp || '6281234567890';
    const message = encodeURIComponent(
        'Assalamu’alaikum, saya ingin konsultasi paket haji & umroh Ajmal Noor Wisata.',
    );

    const brandName = setting?.website_name || 'Ajmal Noor Wisata';

    return (
        <FrontendLayout setting={setting}>
            <Head title="Kontak Kami" />

            {/* HERO */}
            <section className="relative overflow-hidden bg-[#003f35] px-5 pb-20 pt-16 md:px-8 md:pb-24 md:pt-20">
                <img
                    src="/images/mekkah.jpg"
                    alt="Kontak Ajmal Noor Wisata"
                    className="absolute inset-0 h-full w-full object-cover opacity-25"
                    onError={(e) => {
                        e.currentTarget.style.display = 'none';
                    }}
                />

                <div className="absolute inset-0 bg-gradient-to-r from-[#002920]/98 via-[#003f35]/92 to-[#003f35]/70" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(232,189,98,0.24),transparent_55%)]" />
                <Pattern className="absolute inset-0 opacity-15" color="rgba(255,255,255,0.25)" />

                <div className="relative mx-auto max-w-[1600px]">
                    <div className="grid items-center gap-10 lg:grid-cols-[1fr_0.75fr]">
                        <div>
                            <div className="mb-6 inline-flex items-center gap-2.5 rounded-full border border-[#e8bd62]/50 bg-white/8 px-4 py-2.5 text-xs font-black uppercase tracking-wide text-[#f5d889] backdrop-blur sm:text-sm">
                                <span className="flex h-6 w-6 items-center justify-center rounded-full border border-[#e8bd62]/70 text-[10px]">
                                    ✦
                                </span>
                                Hubungi Admin Travel
                            </div>

                            <h1 className="max-w-4xl text-4xl font-black leading-tight text-white sm:text-5xl md:text-6xl">
                                Konsultasikan Perjalanan
                                <br />
                                <span className="bg-gradient-to-r from-[#fff3c1] via-[#e8bd62] to-[#fce9a8] bg-clip-text text-transparent">
                                    Haji & Umroh Anda
                                </span>
                            </h1>

                            <p className="mt-6 max-w-2xl text-base leading-8 text-white/80 sm:text-lg">
                                Admin {brandName} siap membantu menjelaskan pilihan paket,
                                jadwal keberangkatan, fasilitas, harga, dan proses pendaftaran
                                jamaah.
                            </p>

                            <div className="mt-8 flex flex-wrap gap-4">
                                <a
                                    href={`https://wa.me/${whatsapp}?text=${message}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex items-center gap-2.5 rounded-full bg-gradient-to-r from-[#c68b27] to-[#f4d27f] px-7 py-4 text-sm font-black text-[#003f35] shadow-xl transition hover:scale-105 sm:text-base"
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
                                <HeroInfo icon="💬" title="WhatsApp Admin" desc="Konsultasi cepat dan mudah" />
                                <HeroInfo icon="📍" title="Alamat Kantor" desc="Kunjungi kantor layanan kami" />
                                <HeroInfo icon="🕋" title="Paket Ibadah" desc="Umroh, Haji Khusus, dan Haji Furoda" />
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

            {/* CONTACT CONTENT */}
            <section className="relative overflow-hidden bg-[#f8f4ec] px-5 py-16 sm:py-20 md:px-8 md:py-24">
                <Pattern className="absolute inset-0 opacity-25" color="rgba(194,139,41,0.1)" />

                <div className="relative mx-auto grid max-w-[1600px] gap-8 lg:grid-cols-[0.9fr_1.1fr]">
                    {/* LEFT INFO */}
                    <div className="space-y-6">
                        <div className="rounded-[32px] border border-[#e8e0d0] bg-white p-6 shadow-sm md:p-8">
                            <p className="text-xs font-black uppercase tracking-widest text-[#c68b27]">
                                INFORMASI KONTAK
                            </p>
                            <h2 className="mt-2 text-3xl font-black text-[#003f35] md:text-4xl">
                                Hubungi {brandName}
                            </h2>
                            <p className="mt-3 text-sm leading-8 text-slate-500 sm:text-base">
                                Silakan hubungi kami untuk informasi paket haji dan umroh,
                                konsultasi keberangkatan, persyaratan dokumen, dan estimasi biaya.
                            </p>

                            <div className="mt-8 grid gap-4">
                                <ContactInfo
                                    icon="📍"
                                    label="Alamat Kantor"
                                    value={setting?.address || 'Alamat kantor belum diatur'}
                                />

                                <ContactInfo
                                    icon="📞"
                                    label="Nomor Telepon"
                                    value={setting?.phone || '-'}
                                />

                                <ContactInfo
                                    icon="💬"
                                    label="WhatsApp"
                                    value={setting?.whatsapp || '-'}
                                />

                                <ContactInfo
                                    icon="✉"
                                    label="Email"
                                    value={setting?.email || '-'}
                                />
                            </div>
                        </div>

                        <div className="relative overflow-hidden rounded-[32px] bg-gradient-to-br from-[#003f35] via-[#004f41] to-[#005a49] p-6 text-white shadow-2xl md:p-8">
                            <div className="absolute right-0 top-0 h-56 w-56 -translate-y-1/3 translate-x-1/3 rounded-full bg-[#e8bd62]/10 blur-3xl" />

                            <div className="relative">
                                <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-[#e8bd62]/40 bg-white/8 px-4 py-2 text-xs font-black text-[#f5d889]">
                                    <span>●</span> Konsultasi Gratis
                                </div>

                                <h3 className="text-2xl font-black leading-tight md:text-3xl">
                                    Butuh Rekomendasi Paket yang Cocok?
                                </h3>

                                <p className="mt-3 text-sm leading-7 text-white/75">
                                    Ceritakan kebutuhan Anda, jumlah jamaah, rencana keberangkatan,
                                    dan budget. Admin kami akan bantu rekomendasikan paket terbaik.
                                </p>

                                <a
                                    href={`https://wa.me/${whatsapp}?text=${message}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="mt-6 inline-flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-[#c68b27] to-[#f4d27f] px-8 py-4 text-sm font-black text-[#003f35] shadow-xl transition hover:scale-105 sm:w-auto"
                                >
                                    Chat WhatsApp Sekarang
                                    <span>→</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {/* RIGHT MAP / FORM STYLE */}
                    <div className="space-y-6">
                        <div className="overflow-hidden rounded-[32px] border border-[#e8e0d0] bg-white p-4 shadow-sm">
                            {setting?.google_maps ? (
                                <div
                                    className="h-[420px] w-full overflow-hidden rounded-[24px] md:h-[560px]"
                                    dangerouslySetInnerHTML={{ __html: setting.google_maps }}
                                />
                            ) : (
                                <div className="flex h-[420px] items-center justify-center rounded-[24px] bg-[#f4fbf8] p-8 text-center md:h-[560px]">
                                    <div>
                                        <div className="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#e6f6ef] text-3xl">
                                            📍
                                        </div>
                                        <h3 className="mt-5 text-2xl font-black text-[#003f35]">
                                            Google Maps belum diatur
                                        </h3>
                                        <p className="mt-3 max-w-md text-sm leading-7 text-slate-500">
                                            Silakan isi embed Google Maps dari admin panel pada menu
                                            Pengaturan Website agar lokasi kantor tampil di halaman ini.
                                        </p>
                                    </div>
                                </div>
                            )}
                        </div>

                        <div className="grid gap-4 sm:grid-cols-3">
                            <MiniCard icon="⚖️" title="Legalitas" desc="Izin resmi travel" />
                            <MiniCard icon="👨‍🏫" title="Pembimbing" desc="Berpengalaman" />
                            <MiniCard icon="💰" title="Transparan" desc="Harga jelas" />
                        </div>
                    </div>
                </div>
            </section>

            {/* CTA BOTTOM */}
            <section className="bg-[#f8f4ec] px-5 pb-16 md:px-8 md:pb-24">
                <div className="mx-auto max-w-[1600px]">
                    <div className="relative overflow-hidden rounded-[36px] bg-gradient-to-br from-[#003f35] via-[#004f41] to-[#005a49] p-8 shadow-2xl sm:p-10 md:p-12">
                        <div className="absolute right-0 top-0 h-64 w-64 -translate-y-1/3 translate-x-1/3 rounded-full bg-[#e8bd62]/10 blur-3xl" />

                        <div className="relative grid items-center gap-8 md:grid-cols-2">
                            <div>
                                <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-[#e8bd62]/40 bg-white/8 px-4 py-2 text-xs font-black text-[#f5d889]">
                                    <span>●</span> Ajmal Noor Wisata
                                </div>

                                <h2 className="text-3xl font-black leading-tight text-white sm:text-4xl md:text-5xl">
                                    Siap Berangkat Umroh atau Haji?
                                </h2>

                                <p className="mt-4 max-w-lg text-sm leading-8 text-white/80 sm:text-base">
                                    Lihat pilihan paket yang tersedia, atau langsung konsultasi
                                    dengan admin untuk mendapatkan informasi terbaru.
                                </p>
                            </div>

                            <div className="flex flex-col gap-4 lg:items-end">
                                <Link
                                    href="/paket-umroh"
                                    className="inline-flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-[#c68b27] to-[#f4d27f] px-8 py-5 text-base font-black text-[#003f35] shadow-xl transition hover:scale-105 lg:w-auto"
                                >
                                    Lihat Paket Umroh
                                </Link>

                                <Link
                                    href="/paket-haji"
                                    className="inline-flex w-full items-center justify-center gap-3 rounded-2xl border-2 border-white/30 px-8 py-5 text-base font-black text-white transition hover:border-white/60 lg:w-auto"
                                >
                                    Lihat Paket Haji
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

function ContactInfo({
    icon,
    label,
    value,
}: {
    icon: string;
    label: string;
    value: string;
}) {
    return (
        <div className="rounded-2xl border border-[#e8e0d0] bg-[#fafaf7] p-5">
            <div className="flex items-start gap-4">
                <span className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#e6f6ef] text-xl">
                    {icon}
                </span>
                <div>
                    <p className="text-xs font-black uppercase tracking-wide text-slate-400">
                        {label}
                    </p>
                    <p className="mt-1 break-words text-sm font-black leading-7 text-[#003f35] sm:text-base">
                        {value}
                    </p>
                </div>
            </div>
        </div>
    );
}

function MiniCard({
    icon,
    title,
    desc,
}: {
    icon: string;
    title: string;
    desc: string;
}) {
    return (
        <div className="rounded-2xl border border-[#e8e0d0] bg-white p-5 text-center shadow-sm">
            <span className="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-[#e6f6ef] text-xl">
                {icon}
            </span>
            <h3 className="mt-3 text-sm font-black text-[#003f35]">
                {title}
            </h3>
            <p className="mt-1 text-xs leading-5 text-slate-500">
                {desc}
            </p>
        </div>
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