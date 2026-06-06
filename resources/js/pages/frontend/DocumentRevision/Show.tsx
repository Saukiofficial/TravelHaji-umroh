import FrontendLayout from '@/components/frontend/FrontendLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { FormEvent } from 'react';

type Setting = {
    website_name?: string | null;
    logo?: string | null;
    whatsapp?: string | null;
    phone?: string | null;
    email?: string | null;
    address?: string | null;
};

type Participant = {
    id: number;
    name?: string | null;
    phone?: string | null;
    email?: string | null;
    revision_token: string;
    registration?: {
        id?: number | null;
        name?: string | null;
        phone?: string | null;
        email?: string | null;
        package_title?: string | null;
    };
};

type RevisionDocument = {
    id: number;
    document_type: string;
    document_label: string;
    file_path?: string | null;
    status?: string | null;
    status_label?: string | null;
    admin_note?: string | null;
};

type PageProps = {
    flash?: {
        success?: string;
        error?: string;
    };
    errors?: Record<string, string>;
};

export default function DocumentRevisionShow({
    setting,
    participant,
    documents = [],
}: {
    setting: Setting;
    participant: Participant;
    documents: RevisionDocument[];
}) {
    const page = usePage<PageProps>();
    const successMessage = page.props.flash?.success;
    const errorMessage = page.props.flash?.error;

    const { data, setData, post, processing, errors } = useForm<{
        jamaah_note: string;
        documents: Record<string, File | null>;
    }>({
        jamaah_note: '',
        documents: {},
    });

    const brandName = setting?.website_name || 'Ajmal Noor Wisata';

    const submit = (e: FormEvent) => {
        e.preventDefault();

        post(`/revisi-dokumen/${participant.revision_token}`, {
            forceFormData: true,
            preserveScroll: true,
        });
    };

    const setDocumentFile = (documentId: number, file: File | null) => {
        setData('documents', {
            ...data.documents,
            [String(documentId)]: file,
        });
    };

    return (
        <FrontendLayout setting={setting}>
            <Head title={`Revisi Dokumen - ${brandName}`} />

            <section className="relative overflow-hidden bg-[#061A35] px-5 pb-20 pt-16 md:px-8 md:pb-24 md:pt-20">
                <img
                    src="/images/mekkah.jpg"
                    alt="Revisi Dokumen Jamaah"
                    className="absolute inset-0 h-full w-full object-cover opacity-22"
                    onError={(e) => {
                        e.currentTarget.style.display = 'none';
                    }}
                />

                <div className="absolute inset-0 bg-gradient-to-r from-[#061A35]/98 via-[#0B2D5B]/92 to-[#004F41]/60" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(214,168,79,0.28),transparent_55%)]" />
                <Pattern className="absolute inset-0 opacity-15" color="rgba(255,255,255,0.25)" />

                <div className="relative mx-auto max-w-[1100px]">
                    <div className="mb-6 inline-flex items-center gap-2.5 rounded-full border border-[#D6A84F]/55 bg-white/8 px-4 py-2.5 text-xs font-black uppercase tracking-wide text-[#F3D58B] backdrop-blur sm:text-sm">
                        <span className="flex h-6 w-6 items-center justify-center rounded-full border border-[#D6A84F]/70 text-[10px]">
                            ✦
                        </span>
                        Revisi Dokumen Jamaah
                    </div>

                    <h1
                        style={{ fontFamily: "'Playfair Display',serif" }}
                        className="max-w-4xl text-4xl font-black leading-tight text-white sm:text-5xl md:text-6xl"
                    >
                        Upload Ulang Dokumen
                        <br />
                        <span className="bg-gradient-to-r from-[#FFFFFF] via-[#F3D58B] to-[#D6A84F] bg-clip-text text-transparent">
                            yang Perlu Diperbaiki
                        </span>
                    </h1>

                    <p className="mt-6 max-w-2xl text-base leading-8 text-white/82 sm:text-lg">
                        Silakan upload ulang dokumen yang ditandai perlu revisi atau ditolak oleh admin.
                        Data pendaftaran Anda tetap aman dan tidak perlu daftar ulang.
                    </p>
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

            <section className="relative overflow-hidden bg-[#FFF8EC] px-5 py-16 md:px-8 md:py-20">
                <Pattern className="absolute inset-0 opacity-30" color="rgba(11,45,91,0.10)" />

                <div className="relative mx-auto grid max-w-[1300px] gap-8 lg:grid-cols-[1fr_360px]">
                    <div className="space-y-6">
                        {successMessage && (
                            <div className="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm font-bold text-emerald-700">
                                {successMessage}
                            </div>
                        )}

                        {errorMessage && (
                            <div className="rounded-3xl border border-red-200 bg-red-50 p-5 text-sm font-bold text-red-700">
                                {errorMessage}
                            </div>
                        )}

                        <div className="rounded-[32px] border border-[#E3EAF5] bg-white p-6 shadow-sm md:p-8">
                            <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                DATA JAMAAH
                            </p>

                            <h2
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-3xl font-black text-[#0B2D5B]"
                            >
                                {participant.name || 'Peserta Jamaah'}
                            </h2>

                            <div className="mt-5 grid gap-4 sm:grid-cols-2">
                                <InfoItem label="Paket" value={participant.registration?.package_title || '-'} />
                                <InfoItem label="Nomor WhatsApp" value={participant.phone || participant.registration?.phone || '-'} />
                                <InfoItem label="Email" value={participant.email || participant.registration?.email || '-'} />
                                <InfoItem label="Status" value="Revisi Dokumen" />
                            </div>
                        </div>

                        <form onSubmit={submit} className="rounded-[32px] border border-[#E3EAF5] bg-white p-6 shadow-sm md:p-8">
                            <div className="mb-6">
                                <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                    DOKUMEN YANG PERLU DIREVISI
                                </p>

                                <h2
                                    style={{ fontFamily: "'Playfair Display',serif" }}
                                    className="mt-2 text-3xl font-black text-[#0B2D5B]"
                                >
                                    Upload Dokumen Baru
                                </h2>

                                <p className="mt-3 text-sm leading-7 text-[#64748B]">
                                    Upload hanya dokumen yang diminta oleh admin. Format file JPG, JPEG, PNG, WEBP, atau PDF.
                                    Maksimal 4 MB per dokumen.
                                </p>
                            </div>

                            {documents.length > 0 ? (
                                <div className="space-y-5">
                                    {documents.map((document) => (
                                        <div
                                            key={document.id}
                                            className="rounded-3xl border border-[#E3EAF5] bg-[#F7FAFF] p-5"
                                        >
                                            <div className="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                                <div>
                                                    <div className="inline-flex rounded-full bg-[#EAF2FF] px-3 py-1.5 text-xs font-black text-[#0B2D5B]">
                                                        {document.status_label || 'Perlu Revisi'}
                                                    </div>

                                                    <h3 className="mt-3 text-xl font-black text-[#0B2D5B]">
                                                        {document.document_label}
                                                    </h3>

                                                    {document.admin_note && (
                                                        <div className="mt-3 rounded-2xl border border-[#D6A84F]/35 bg-[#FFF8EC] p-4">
                                                            <p className="text-xs font-black uppercase tracking-wide text-[#B7791F]">
                                                                Catatan Admin
                                                            </p>
                                                            <p className="mt-1 text-sm leading-7 text-[#475569]">
                                                                {document.admin_note}
                                                            </p>
                                                        </div>
                                                    )}

                                                    {document.file_path && (
                                                        <a
                                                            href={`/storage/${document.file_path}`}
                                                            target="_blank"
                                                            rel="noreferrer"
                                                            className="mt-4 inline-flex rounded-2xl border border-[#0B2D5B] px-4 py-2 text-xs font-black text-[#0B2D5B] transition hover:bg-[#EAF2FF]"
                                                        >
                                                            Lihat File Lama
                                                        </a>
                                                    )}
                                                </div>
                                            </div>

                                            <div className="mt-5">
                                                <label className="text-sm font-black text-[#0B2D5B]">
                                                    Upload File Baru
                                                </label>

                                                <input
                                                    type="file"
                                                    accept=".jpg,.jpeg,.png,.webp,.pdf"
                                                    onChange={(e) => setDocumentFile(document.id, e.target.files?.[0] || null)}
                                                    className="mt-2 w-full rounded-2xl border border-[#E3EAF5] bg-white px-4 py-3 text-xs text-[#475569] outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-[#0B2D5B] file:px-4 file:py-2 file:text-xs file:font-black file:text-white hover:file:bg-[#061A35] focus:border-[#0B2D5B] focus:ring-4 focus:ring-[#0B2D5B]/10"
                                                />

                                                {data.documents[String(document.id)] && (
                                                    <div className="mt-3 rounded-xl bg-[#EAF2FF] px-4 py-3 text-xs font-semibold text-[#0B2D5B]">
                                                        File dipilih: {data.documents[String(document.id)]?.name}
                                                    </div>
                                                )}

                                                {errors[`documents.${document.id}`] && (
                                                    <p className="mt-2 text-sm font-semibold text-red-600">
                                                        {errors[`documents.${document.id}`]}
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    ))}

                                    <div>
                                        <label className="text-sm font-black text-[#0B2D5B]">
                                            Catatan Jamaah
                                        </label>

                                        <textarea
                                            rows={4}
                                            value={data.jamaah_note}
                                            onChange={(e) => setData('jamaah_note', e.target.value)}
                                            placeholder="Contoh: Dokumen sudah saya upload ulang, mohon dicek kembali."
                                            className="mt-2 w-full rounded-2xl border border-[#E3EAF5] bg-white px-4 py-3 text-sm text-[#102033] outline-none transition focus:border-[#0B2D5B] focus:ring-4 focus:ring-[#0B2D5B]/10"
                                        />

                                        {errors.jamaah_note && (
                                            <p className="mt-2 text-sm font-semibold text-red-600">
                                                {errors.jamaah_note}
                                            </p>
                                        )}
                                    </div>

                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full rounded-2xl bg-[#0B2D5B] px-6 py-4 text-sm font-black text-white shadow-lg transition hover:bg-[#061A35] disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        {processing ? 'Mengirim Revisi...' : 'Kirim Revisi Dokumen'}
                                    </button>
                                </div>
                            ) : (
                                <div className="rounded-3xl border border-dashed border-[#D6A84F]/50 bg-[#FFF8EC] p-8 text-center">
                                    <h3
                                        style={{ fontFamily: "'Playfair Display',serif" }}
                                        className="text-2xl font-black text-[#0B2D5B]"
                                    >
                                        Tidak Ada Dokumen yang Perlu Direvisi
                                    </h3>
                                    <p className="mt-3 text-sm leading-7 text-[#64748B]">
                                        Semua dokumen Anda sudah tidak memiliki status perlu revisi atau ditolak.
                                    </p>

                                    <Link
                                        href="/"
                                        className="mt-6 inline-flex rounded-2xl bg-[#0B2D5B] px-6 py-3 text-sm font-black text-white"
                                    >
                                        Kembali ke Beranda
                                    </Link>
                                </div>
                            )}
                        </form>
                    </div>

                    <aside className="h-fit space-y-6 lg:sticky lg:top-28">
                        <div className="rounded-[28px] border border-[#E3EAF5] bg-white p-6 shadow-sm">
                            <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                Informasi
                            </p>

                            <h2
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-2xl font-black text-[#0B2D5B]"
                            >
                                Tidak Perlu Daftar Ulang
                            </h2>

                            <p className="mt-3 text-sm leading-7 text-[#64748B]">
                                Halaman ini hanya digunakan untuk upload ulang dokumen yang bermasalah.
                                Data pendaftaran dan data peserta tetap sama.
                            </p>
                        </div>

                        <div className="rounded-[28px] border border-[#D6A84F]/35 bg-gradient-to-br from-[#061A35] via-[#0B2D5B] to-[#123F7A] p-6 text-white shadow-sm">
                            <p className="text-xs font-black uppercase tracking-widest text-[#F3D58B]">
                                {brandName}
                            </p>

                            <h3
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-2xl font-black"
                            >
                                Butuh Bantuan?
                            </h3>

                            <p className="mt-3 text-sm leading-7 text-white/75">
                                Jika bingung mengupload dokumen, silakan hubungi admin melalui WhatsApp.
                            </p>

                            <a
                                href={`https://wa.me/${setting?.whatsapp || '6281234567890'}?text=${encodeURIComponent(
                                    'Assalamu’alaikum, saya butuh bantuan untuk revisi dokumen pendaftaran.',
                                )}`}
                                target="_blank"
                                rel="noreferrer"
                                className="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-[#D6A84F] to-[#F3D58B] px-6 py-4 text-sm font-black text-[#0B2D5B]"
                            >
                                Hubungi Admin
                            </a>
                        </div>
                    </aside>
                </div>
            </section>
        </FrontendLayout>
    );
}

function InfoItem({
    label,
    value,
}: {
    label: string;
    value: string;
}) {
    return (
        <div className="rounded-2xl border border-[#E3EAF5] bg-[#F7FAFF] p-4">
            <p className="text-xs font-black uppercase tracking-wide text-[#64748B]">
                {label}
            </p>
            <p className="mt-1 font-black text-[#0B2D5B]">{value}</p>
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