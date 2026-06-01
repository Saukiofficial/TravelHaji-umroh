import FrontendLayout from '@/components/frontend/FrontendLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { FormEvent } from 'react';

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
    facilities?: string | null;
    itinerary?: string | null;
    requirements?: string | null;
    description?: string | null;
    image?: string | null;
};

type FlashProps = {
    flash?: {
        success?: string;
    };
};

type DocumentKey =
    | 'ktp'
    | 'paspor'
    | 'kartu_keluarga'
    | 'buku_nikah_akta_ijazah'
    | 'pas_foto'
    | 'sertifikat_vaksin'
    | 'dokumen_tambahan';

type DocumentsState = Record<DocumentKey, File | null>;

type ParticipantForm = {
    order_number: number;
    name: string;
    gender: string;
    birth_place: string;
    birth_date: string;
    phone: string;
    email: string;
    nik: string;
    passport_number: string;
    passport_issued_at: string;
    passport_expired_at: string;
    address: string;
    emergency_contact_name: string;
    emergency_contact_phone: string;
    health_note: string;
    note: string;
    documents: DocumentsState;
};

const DOCUMENT_TYPES: {
    key: DocumentKey;
    label: string;
    description: string;
    required?: boolean;
}[] = [
    {
        key: 'ktp',
        label: 'KTP / Identitas',
        description: 'Upload KTP jamaah dalam format JPG, PNG, WEBP, atau PDF.',
        required: true,
    },
    {
        key: 'paspor',
        label: 'Paspor',
        description: 'Upload halaman identitas paspor jika sudah tersedia.',
        required: true,
    },
    {
        key: 'kartu_keluarga',
        label: 'Kartu Keluarga',
        description: 'Upload Kartu Keluarga untuk kelengkapan data jamaah.',
        required: true,
    },
    {
        key: 'buku_nikah_akta_ijazah',
        label: 'Buku Nikah / Akta / Ijazah',
        description: 'Upload salah satu dokumen pendukung sesuai kebutuhan.',
    },
    {
        key: 'pas_foto',
        label: 'Pas Foto',
        description: 'Upload pas foto jamaah jika sudah tersedia.',
    },
    {
        key: 'sertifikat_vaksin',
        label: 'Sertifikat Vaksin',
        description: 'Upload sertifikat vaksin jika diperlukan.',
    },
    {
        key: 'dokumen_tambahan',
        label: 'Dokumen Tambahan',
        description: 'Upload dokumen lain yang diminta admin.',
    },
];

const emptyDocuments = (): DocumentsState => ({
    ktp: null,
    paspor: null,
    kartu_keluarga: null,
    buku_nikah_akta_ijazah: null,
    pas_foto: null,
    sertifikat_vaksin: null,
    dokumen_tambahan: null,
});

const makeParticipant = (orderNumber: number): ParticipantForm => ({
    order_number: orderNumber,
    name: '',
    gender: '',
    birth_place: '',
    birth_date: '',
    phone: '',
    email: '',
    nik: '',
    passport_number: '',
    passport_issued_at: '',
    passport_expired_at: '',
    address: '',
    emergency_contact_name: '',
    emergency_contact_phone: '',
    health_note: '',
    note: '',
    documents: emptyDocuments(),
});

export default function PackageShow({
    setting,
    packageData,
    relatedPackages = [],
}: {
    setting: any;
    packageData: PackageItem;
    relatedPackages: PackageItem[];
}) {
    const page = usePage<FlashProps>();
    const successMessage = page.props.flash?.success;

    const whatsapp = setting?.whatsapp || '6281234567890';
    const message = encodeURIComponent(
        `Assalamu’alaikum, saya ingin konsultasi paket ${packageData.title}.`,
    );

    const { data, setData, post, processing, errors, reset } = useForm({
        package_id: packageData.id,
        name: '',
        phone: '',
        email: '',
        address: '',
        total_participants: 1,
        note: '',
        participants: [makeParticipant(1)],
    });

    const isHaji = packageData.type?.toLowerCase() === 'haji';

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

    const updateParticipantCount = (value: number) => {
        const count = Math.max(1, Math.min(Number(value || 1), 20));
        const currentParticipants = data.participants;

        let nextParticipants = [...currentParticipants];

        if (count > currentParticipants.length) {
            for (let i = currentParticipants.length + 1; i <= count; i++) {
                nextParticipants.push(makeParticipant(i));
            }
        }

        if (count < currentParticipants.length) {
            nextParticipants = nextParticipants.slice(0, count);
        }

        nextParticipants = nextParticipants.map((participant, index) => ({
            ...participant,
            order_number: index + 1,
        }));

        setData({
            ...data,
            total_participants: count,
            participants: nextParticipants,
        });
    };

    const updateParticipant = (
        index: number,
        key: keyof ParticipantForm,
        value: string | number | DocumentsState,
    ) => {
        const nextParticipants = [...data.participants];

        nextParticipants[index] = {
            ...nextParticipants[index],
            [key]: value,
        };

        setData('participants', nextParticipants);
    };

    const setParticipantDocument = (
        participantIndex: number,
        documentKey: DocumentKey,
        file: File | null,
    ) => {
        const nextParticipants = [...data.participants];

        nextParticipants[participantIndex] = {
            ...nextParticipants[participantIndex],
            documents: {
                ...nextParticipants[participantIndex].documents,
                [documentKey]: file,
            },
        };

        setData('participants', nextParticipants);
    };

    const submit = (e: FormEvent) => {
        e.preventDefault();

        post('/pendaftaran', {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                reset();
            },
        });
    };

    return (
        <FrontendLayout setting={setting}>
            <Head title={packageData.title} />

            {/* HERO */}
            <section className="relative overflow-hidden bg-[#061A35] px-5 pb-16 pt-16 md:px-8 md:pb-20 md:pt-20">
                {packageData.image ? (
                    <img
                        src={`/storage/${packageData.image}`}
                        alt={packageData.title}
                        className="absolute inset-0 h-full w-full object-cover opacity-26"
                    />
                ) : (
                    <img
                        src="/images/mekkah.jpg"
                        alt={packageData.title}
                        className="absolute inset-0 h-full w-full object-cover opacity-22"
                        onError={(e) => {
                            e.currentTarget.style.display = 'none';
                        }}
                    />
                )}

                <div className="absolute inset-0 bg-gradient-to-r from-[#061A35]/98 via-[#0B2D5B]/92 to-[#004F41]/60" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_25%,rgba(214,168,79,0.28),transparent_55%)]" />
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_20%_85%,rgba(234,242,255,0.10),transparent_48%)]" />
                <div
                    className="absolute inset-0 opacity-15"
                    style={{
                        backgroundImage:
                            'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.25) 1px, transparent 0)',
                        backgroundSize: '32px 32px',
                    }}
                />

                <div className="relative mx-auto max-w-[1600px]">
                    <div className="max-w-4xl">
                        <Link
                            href={isHaji ? '/paket-haji' : '/paket-umroh'}
                            className="mb-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-black text-white backdrop-blur transition hover:bg-white/15"
                        >
                            ← Kembali ke {isHaji ? 'Paket Haji' : 'Paket Umroh'}
                        </Link>

                        <div
                            className={`mb-5 inline-flex rounded-full px-4 py-2 text-xs font-black uppercase tracking-wide shadow ${
                                isHaji ? 'bg-[#FFF3DF] text-[#A86808]' : 'bg-[#EAF2FF] text-[#0B2D5B]'
                            }`}
                        >
                            {packageData.type?.toUpperCase()}
                        </div>

                        <h1
                            style={{ fontFamily: "'Playfair Display',serif" }}
                            className="text-4xl font-black leading-tight text-white sm:text-5xl md:text-6xl"
                        >
                            {packageData.title}
                        </h1>

                        <p className="mt-5 text-3xl font-black text-[#F3D58B] md:text-4xl">
                            {formatRupiah(packageData.price)}
                        </p>

                        <p className="mt-5 max-w-3xl text-base leading-8 text-white/82 sm:text-lg">
                            {packageData.description ||
                                'Paket perjalanan ibadah dengan pelayanan terbaik, jadwal jelas, dan pembimbing berpengalaman.'}
                        </p>

                        <div className="mt-8 flex flex-wrap gap-4">
                            <a
                                href={`https://wa.me/${whatsapp}?text=${message}`}
                                target="_blank"
                                rel="noreferrer"
                                className="inline-flex items-center gap-2.5 rounded-full bg-gradient-to-r from-[#D6A84F] to-[#F3D58B] px-7 py-4 text-sm font-black text-[#0B2D5B] shadow-xl transition hover:scale-105 sm:text-base"
                            >
                                Konsultasi Paket Ini
                                <span>→</span>
                            </a>

                            <a
                                href="#form-pendaftaran"
                                className="inline-flex items-center gap-2.5 rounded-full border border-white/25 bg-white/10 px-7 py-4 text-sm font-black text-white backdrop-blur transition hover:bg-white/18 sm:text-base"
                            >
                                Daftar Sekarang
                            </a>
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

            {/* CONTENT */}
            <section className="relative overflow-hidden bg-[#FFF8EC] px-5 py-16 md:px-8 md:py-20">
                <div
                    className="absolute inset-0 opacity-30"
                    style={{
                        backgroundImage:
                            'radial-gradient(circle at 1px 1px, rgba(11,45,91,0.10) 1px, transparent 0)',
                        backgroundSize: '28px 28px',
                    }}
                />

                <div className="relative mx-auto grid max-w-[1600px] gap-8 lg:grid-cols-[1fr_540px]">
                    <div className="space-y-8">
                        <div className="overflow-hidden rounded-[32px] border border-[#E3EAF5] bg-white shadow-sm">
                            <div className="relative h-72 bg-[#EAF2FF] md:h-[460px]">
                                {packageData.image ? (
                                    <img
                                        src={`/storage/${packageData.image}`}
                                        alt={packageData.title}
                                        className="h-full w-full object-cover"
                                    />
                                ) : (
                                    <div className="flex h-full items-center justify-center text-[#0B2D5B]/40">
                                        Gambar Paket
                                    </div>
                                )}

                                <div
                                    className={`absolute left-5 top-5 rounded-full px-4 py-2 text-xs font-black shadow ${
                                        isHaji ? 'bg-[#FFF3DF] text-[#A86808]' : 'bg-[#EAF2FF] text-[#0B2D5B]'
                                    }`}
                                >
                                    {packageData.type?.toUpperCase()}
                                </div>
                            </div>

                            <div className="p-5 md:p-7">
                                <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                    <InfoBox icon="📅" label="Keberangkatan" value={formatDate(packageData.departure_date)} />
                                    <InfoBox icon="🕋" label="Durasi" value={`${packageData.duration_days || '-'} Hari`} />
                                    <InfoBox icon="✈️" label="Maskapai" value={packageData.airline || '-'} />
                                    <InfoBox icon="🏨" label="Hotel Makkah" value={packageData.makkah_hotel || '-'} />
                                    <InfoBox icon="🏨" label="Hotel Madinah" value={packageData.madinah_hotel || '-'} />
                                    <InfoBox icon="👥" label="Seat" value={packageData.seat ? `${packageData.seat} Jamaah` : 'Tersedia'} />
                                </div>
                            </div>
                        </div>

                        <ContentSection title="Deskripsi Paket" content={packageData.description} />
                        <ContentSection title="Fasilitas Paket" content={packageData.facilities} />
                        <ContentSection title="Itinerary Perjalanan" content={packageData.itinerary} />
                        <ContentSection title="Syarat Pendaftaran" content={packageData.requirements} />

                        {relatedPackages.length > 0 && (
                            <div>
                                <div className="mb-6">
                                    <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                        PAKET TERKAIT
                                    </p>
                                    <h2
                                        style={{ fontFamily: "'Playfair Display',serif" }}
                                        className="mt-2 text-3xl font-black text-[#0B2D5B]"
                                    >
                                        Paket Lain yang Mungkin Cocok
                                    </h2>
                                </div>

                                <div className="grid gap-5 md:grid-cols-3">
                                    {relatedPackages.map((item) => (
                                        <Link
                                            key={item.id}
                                            href={`/paket/${item.slug}`}
                                            className="rounded-[24px] border border-[#E3EAF5] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-[#D6A84F]/55 hover:shadow-lg"
                                        >
                                            <span
                                                className={`rounded-full px-3 py-1.5 text-xs font-black ${
                                                    item.type?.toLowerCase() === 'haji'
                                                        ? 'bg-[#FFF3DF] text-[#A86808]'
                                                        : 'bg-[#EAF2FF] text-[#0B2D5B]'
                                                }`}
                                            >
                                                {item.type?.toUpperCase()}
                                            </span>

                                            <h3
                                                style={{ fontFamily: "'Playfair Display',serif" }}
                                                className="mt-4 text-lg font-black leading-snug text-[#0B2D5B]"
                                            >
                                                {item.title}
                                            </h3>

                                            <p className="mt-3 text-xl font-black text-[#B7791F]">
                                                {formatRupiah(item.price)}
                                            </p>

                                            <p className="mt-3 text-sm font-black text-[#0B2D5B] transition hover:text-[#B7791F]">
                                                Lihat Detail →
                                            </p>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* FORM */}
                    <aside
                        id="form-pendaftaran"
                        className="h-fit rounded-[32px] border border-[#E3EAF5] bg-white p-5 shadow-xl lg:sticky lg:top-28 md:p-6"
                    >
                        <div className="mb-5 rounded-3xl bg-[#EAF2FF] p-5">
                            <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                FORM PENDAFTARAN
                            </p>
                            <h2
                                style={{ fontFamily: "'Playfair Display',serif" }}
                                className="mt-2 text-2xl font-black text-[#0B2D5B]"
                            >
                                Daftar Paket Ini
                            </h2>
                            <p className="mt-2 text-sm leading-7 text-[#64748B]">
                                Jika jumlah peserta lebih dari 1, sistem akan otomatis menampilkan
                                form data jamaah sesuai jumlah peserta.
                            </p>
                        </div>

                        {successMessage && (
                            <div className="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-700">
                                {successMessage}
                            </div>
                        )}

                        <form onSubmit={submit} className="space-y-5">
                            <div className="rounded-[24px] border border-[#E3EAF5] bg-[#F7FAFF] p-4">
                                <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                                    DATA BOOKING
                                </p>

                                <div className="mt-4 space-y-4">
                                    <Input
                                        label="Nama Pendaftar Utama"
                                        value={data.name}
                                        onChange={(value) => setData('name', value)}
                                        error={errors.name}
                                        required
                                    />

                                    <Input
                                        label="Nomor WhatsApp Pendaftar"
                                        value={data.phone}
                                        onChange={(value) => setData('phone', value)}
                                        error={errors.phone}
                                        required
                                    />

                                    <Input
                                        label="Email Pendaftar"
                                        type="email"
                                        value={data.email}
                                        onChange={(value) => setData('email', value)}
                                        error={errors.email}
                                    />

                                    <Input
                                        label="Jumlah Peserta"
                                        type="number"
                                        value={String(data.total_participants)}
                                        onChange={(value) => updateParticipantCount(Number(value))}
                                        error={errors.total_participants}
                                        required
                                    />

                                    <Textarea
                                        label="Alamat Pendaftar"
                                        value={data.address}
                                        onChange={(value) => setData('address', value)}
                                        error={errors.address}
                                    />

                                    <Textarea
                                        label="Catatan Booking"
                                        value={data.note}
                                        onChange={(value) => setData('note', value)}
                                        error={errors.note}
                                    />
                                </div>
                            </div>

                            <div className="space-y-5">
                                {data.participants.map((participant, index) => (
                                    <ParticipantFormCard
                                        key={index}
                                        index={index}
                                        participant={participant}
                                        errors={errors as Record<string, string>}
                                        onChange={(key, value) => updateParticipant(index, key, value)}
                                        onDocumentChange={(documentKey, file) =>
                                            setParticipantDocument(index, documentKey, file)
                                        }
                                    />
                                ))}
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full rounded-2xl bg-[#0B2D5B] px-6 py-4 text-sm font-black text-white shadow-lg transition hover:bg-[#061A35] disabled:cursor-not-allowed disabled:opacity-60"
                            >
                                {processing ? 'Mengirim Pendaftaran...' : 'Kirim Pendaftaran'}
                            </button>

                            <a
                                href={`https://wa.me/${whatsapp}?text=${message}`}
                                target="_blank"
                                rel="noreferrer"
                                className="inline-flex w-full items-center justify-center rounded-2xl border border-[#0B2D5B] px-6 py-4 text-sm font-black text-[#0B2D5B] transition hover:bg-[#EAF2FF]"
                            >
                                Tanya Admin via WhatsApp
                            </a>
                        </form>
                    </aside>
                </div>
            </section>
        </FrontendLayout>
    );
}

function ParticipantFormCard({
    index,
    participant,
    errors,
    onChange,
    onDocumentChange,
}: {
    index: number;
    participant: ParticipantForm;
    errors: Record<string, string>;
    onChange: (key: keyof ParticipantForm, value: string | number | DocumentsState) => void;
    onDocumentChange: (documentKey: DocumentKey, file: File | null) => void;
}) {
    const prefix = `participants.${index}`;

    return (
        <div className="rounded-[24px] border border-[#E3EAF5] bg-white p-4 shadow-sm">
            <div className="mb-4 flex items-center justify-between gap-3 rounded-2xl bg-[#EAF2FF] px-4 py-3">
                <div>
                    <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                        DATA JAMAAH
                    </p>
                    <h3
                        style={{ fontFamily: "'Playfair Display',serif" }}
                        className="text-lg font-black text-[#0B2D5B]"
                    >
                        Peserta {index + 1}
                    </h3>
                </div>

                <span className="rounded-full bg-[#0B2D5B] px-3 py-1.5 text-xs font-black text-white">
                    Jamaah
                </span>
            </div>

            <div className="space-y-4">
                <Input
                    label="Nama Lengkap Peserta"
                    value={participant.name}
                    onChange={(value) => onChange('name', value)}
                    error={errors[`${prefix}.name`]}
                    required
                />

                <div>
                    <label className="text-sm font-black text-[#0B2D5B]">
                        Jenis Kelamin
                    </label>
                    <select
                        value={participant.gender}
                        onChange={(e) => onChange('gender', e.target.value)}
                        className="mt-1 w-full rounded-2xl border border-[#E3EAF5] bg-white px-4 py-3 text-sm text-[#102033] outline-none transition focus:border-[#0B2D5B] focus:ring-4 focus:ring-[#0B2D5B]/10"
                    >
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    {errors[`${prefix}.gender`] && <ErrorText message={errors[`${prefix}.gender`]} />}
                </div>

                <Input
                    label="Tempat Lahir"
                    value={participant.birth_place}
                    onChange={(value) => onChange('birth_place', value)}
                    error={errors[`${prefix}.birth_place`]}
                />

                <Input
                    label="Tanggal Lahir"
                    type="date"
                    value={participant.birth_date}
                    onChange={(value) => onChange('birth_date', value)}
                    error={errors[`${prefix}.birth_date`]}
                />

                <Input
                    label="NIK"
                    value={participant.nik}
                    onChange={(value) => onChange('nik', value)}
                    error={errors[`${prefix}.nik`]}
                />

                <Input
                    label="Nomor WhatsApp Peserta"
                    value={participant.phone}
                    onChange={(value) => onChange('phone', value)}
                    error={errors[`${prefix}.phone`]}
                />

                <Input
                    label="Email Peserta"
                    type="email"
                    value={participant.email}
                    onChange={(value) => onChange('email', value)}
                    error={errors[`${prefix}.email`]}
                />

                <Textarea
                    label="Alamat Peserta"
                    value={participant.address}
                    onChange={(value) => onChange('address', value)}
                    error={errors[`${prefix}.address`]}
                />

                <div className="rounded-2xl border border-[#E3EAF5] bg-[#F7FAFF] p-4">
                    <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                        DATA PASPOR
                    </p>

                    <div className="mt-4 space-y-4">
                        <Input
                            label="Nomor Paspor"
                            value={participant.passport_number}
                            onChange={(value) => onChange('passport_number', value)}
                            error={errors[`${prefix}.passport_number`]}
                        />

                        <Input
                            label="Tanggal Terbit Paspor"
                            type="date"
                            value={participant.passport_issued_at}
                            onChange={(value) => onChange('passport_issued_at', value)}
                            error={errors[`${prefix}.passport_issued_at`]}
                        />

                        <Input
                            label="Tanggal Expired Paspor"
                            type="date"
                            value={participant.passport_expired_at}
                            onChange={(value) => onChange('passport_expired_at', value)}
                            error={errors[`${prefix}.passport_expired_at`]}
                        />
                    </div>
                </div>

                <div className="rounded-2xl border border-[#E3EAF5] bg-[#F7FAFF] p-4">
                    <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                        KONTAK DARURAT & CATATAN
                    </p>

                    <div className="mt-4 space-y-4">
                        <Input
                            label="Nama Kontak Darurat"
                            value={participant.emergency_contact_name}
                            onChange={(value) => onChange('emergency_contact_name', value)}
                            error={errors[`${prefix}.emergency_contact_name`]}
                        />

                        <Input
                            label="Nomor Kontak Darurat"
                            value={participant.emergency_contact_phone}
                            onChange={(value) => onChange('emergency_contact_phone', value)}
                            error={errors[`${prefix}.emergency_contact_phone`]}
                        />

                        <Textarea
                            label="Catatan Kesehatan"
                            value={participant.health_note}
                            onChange={(value) => onChange('health_note', value)}
                            error={errors[`${prefix}.health_note`]}
                        />

                        <Textarea
                            label="Catatan Peserta"
                            value={participant.note}
                            onChange={(value) => onChange('note', value)}
                            error={errors[`${prefix}.note`]}
                        />
                    </div>
                </div>

                <div className="rounded-2xl border border-[#E3EAF5] bg-[#F7FAFF] p-4">
                    <p className="text-xs font-black uppercase tracking-widest text-[#D6A84F]">
                        DOKUMEN PESERTA {index + 1}
                    </p>
                    <p className="mt-1 text-xs leading-6 text-[#64748B]">
                        Upload dokumen khusus untuk peserta ini. Format JPG, PNG, WEBP, atau PDF.
                        Maksimal 4 MB per dokumen.
                    </p>

                    <div className="mt-4 space-y-4">
                        {DOCUMENT_TYPES.map((document) => (
                            <DocumentUploadField
                                key={document.key}
                                label={document.label}
                                description={document.description}
                                required={document.required}
                                file={participant.documents[document.key]}
                                error={errors[`${prefix}.documents.${document.key}`]}
                                onChange={(file) => onDocumentChange(document.key, file)}
                            />
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}

function InfoBox({
    icon,
    label,
    value,
}: {
    icon: string;
    label: string;
    value: string;
}) {
    return (
        <div className="rounded-2xl border border-[#E3EAF5] bg-[#F7FAFF] p-4">
            <div className="flex items-start gap-3">
                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EAF2FF] text-lg">
                    {icon}
                </span>
                <div>
                    <p className="text-xs font-black uppercase tracking-wide text-[#64748B]">
                        {label}
                    </p>
                    <p className="mt-1 font-black text-[#0B2D5B]">{value}</p>
                </div>
            </div>
        </div>
    );
}

function ContentSection({
    title,
    content,
}: {
    title: string;
    content?: string | null;
}) {
    if (!content) return null;

    return (
        <div className="rounded-[28px] border border-[#E3EAF5] bg-white p-6 shadow-sm md:p-7">
            <h2
                style={{ fontFamily: "'Playfair Display',serif" }}
                className="text-2xl font-black text-[#0B2D5B] md:text-3xl"
            >
                {title}
            </h2>
            <div className="mt-4 whitespace-pre-line text-sm leading-8 text-[#475569] md:text-base">
                {content}
            </div>
        </div>
    );
}

function Input({
    label,
    value,
    onChange,
    error,
    type = 'text',
    required = false,
}: {
    label: string;
    value: string;
    onChange: (value: string) => void;
    error?: string;
    type?: string;
    required?: boolean;
}) {
    return (
        <div>
            <label className="text-sm font-black text-[#0B2D5B]">
                {label} {required && <span className="text-red-600">*</span>}
            </label>
            <input
                type={type}
                value={value}
                onChange={(e) => onChange(e.target.value)}
                className="mt-1 w-full rounded-2xl border border-[#E3EAF5] bg-white px-4 py-3 text-sm text-[#102033] outline-none transition focus:border-[#0B2D5B] focus:ring-4 focus:ring-[#0B2D5B]/10"
            />
            {error && <ErrorText message={error} />}
        </div>
    );
}

function Textarea({
    label,
    value,
    onChange,
    error,
}: {
    label: string;
    value: string;
    onChange: (value: string) => void;
    error?: string;
}) {
    return (
        <div>
            <label className="text-sm font-black text-[#0B2D5B]">
                {label}
            </label>
            <textarea
                value={value}
                onChange={(e) => onChange(e.target.value)}
                rows={3}
                className="mt-1 w-full rounded-2xl border border-[#E3EAF5] bg-white px-4 py-3 text-sm text-[#102033] outline-none transition focus:border-[#0B2D5B] focus:ring-4 focus:ring-[#0B2D5B]/10"
            />
            {error && <ErrorText message={error} />}
        </div>
    );
}

function DocumentUploadField({
    label,
    description,
    required,
    file,
    error,
    onChange,
}: {
    label: string;
    description: string;
    required?: boolean;
    file: File | null;
    error?: string;
    onChange: (file: File | null) => void;
}) {
    return (
        <div className="rounded-2xl border border-[#E3EAF5] bg-white p-4">
            <div className="mb-3">
                <label className="text-sm font-black text-[#0B2D5B]">
                    {label} {required && <span className="text-red-600">*</span>}
                </label>
                <p className="mt-1 text-xs leading-5 text-[#64748B]">
                    {description}
                </p>
            </div>

            <input
                type="file"
                accept=".jpg,.jpeg,.png,.webp,.pdf"
                onChange={(e) => onChange(e.target.files?.[0] || null)}
                className="w-full rounded-2xl border border-[#E3EAF5] bg-white px-4 py-3 text-xs text-[#475569] outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-[#0B2D5B] file:px-4 file:py-2 file:text-xs file:font-black file:text-white hover:file:bg-[#061A35] focus:border-[#0B2D5B] focus:ring-4 focus:ring-[#0B2D5B]/10"
            />

            {file && (
                <div className="mt-3 rounded-xl bg-[#EAF2FF] px-4 py-3 text-xs font-semibold text-[#0B2D5B]">
                    File dipilih: {file.name}
                </div>
            )}

            {error && <ErrorText message={error} />}
        </div>
    );
}

function ErrorText({ message }: { message: string }) {
    return (
        <p className="mt-1 text-sm font-semibold text-red-600">
            {message}
        </p>
    );
}