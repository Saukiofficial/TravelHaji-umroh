<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\RegistrationDocument;
use App\Models\RegistrationDocumentRevision;
use App\Models\RegistrationParticipant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentRevisionController extends Controller
{
    public function show(string $token)
    {
        $participant = RegistrationParticipant::query()
            ->with([
                'registration.package',
                'documents',
            ])
            ->where('revision_token', $token)
            ->firstOrFail();

        $revisionDocuments = $participant->documents
            ->filter(function ($document) {
                return in_array($this->normalizeStatus($document->status), [
                    'perlu_revisi',
                    'ditolak',
                ], true);
            })
            ->values()
            ->map(function ($document) {
                return [
                    'id' => $document->id,
                    'document_type' => $document->document_type,
                    'document_label' => $this->getDocumentLabel($document->document_type),
                    'file_path' => $document->file_path,
                    'status' => $document->status,
                    'status_label' => $this->getStatusLabel($document->status),
                    'admin_note' => $document->admin_note,
                ];
            });

        return Inertia::render('frontend/DocumentRevision/Show', [
            'setting' => Setting::query()->first(),

            'participant' => [
                'id' => $participant->id,
                'name' => $participant->name,
                'phone' => $participant->phone,
                'email' => $participant->email,
                'revision_token' => $participant->revision_token,
                'registration' => [
                    'id' => $participant->registration?->id,
                    'name' => $participant->registration?->name,
                    'phone' => $participant->registration?->phone,
                    'email' => $participant->registration?->email,
                    'package_title' => $participant->registration?->package?->title,
                ],
            ],

            'documents' => $revisionDocuments,
        ]);
    }

    public function update(Request $request, string $token)
    {
        $participant = RegistrationParticipant::query()
            ->with(['registration', 'documents'])
            ->where('revision_token', $token)
            ->firstOrFail();

        $documentsNeedRevision = $participant->documents
            ->filter(function ($document) {
                return in_array($this->normalizeStatus($document->status), [
                    'perlu_revisi',
                    'ditolak',
                ], true);
            })
            ->values();

        if ($documentsNeedRevision->isEmpty()) {
            return back()->with('success', 'Tidak ada dokumen yang perlu direvisi.');
        }

        $rules = [
            'jamaah_note' => ['nullable', 'string', 'max:1000'],
        ];

        foreach ($documentsNeedRevision as $document) {
            $rules['documents.' . $document->id] = [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:4096',
            ];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $participant, $documentsNeedRevision, $validated) {
            foreach ($documentsNeedRevision as $document) {
                $uploadedFile = $request->file('documents.' . $document->id);

                if (! $uploadedFile) {
                    continue;
                }

                $oldFilePath = $document->file_path;

                $newFilePath = $uploadedFile->store(
                    'registration-documents/revisions',
                    'public'
                );

                $revisionNumber = RegistrationDocumentRevision::query()
                    ->where('registration_participant_id', $participant->id)
                    ->where('document_type', $document->document_type)
                    ->max('revision_number');

                $revisionNumber = ((int) $revisionNumber) + 1;

                RegistrationDocumentRevision::query()->create([
                    'registration_id' => $participant->registration_id,
                    'registration_participant_id' => $participant->id,
                    'document_type' => $document->document_type,
                    'document_label' => $this->getDocumentLabel($document->document_type),
                    'old_file_path' => $oldFilePath,
                    'new_file_path' => $newFilePath,
                    'old_status' => $document->status,
                    'new_status' => 'menunggu_verifikasi',
                    'admin_note' => $document->admin_note,
                    'jamaah_note' => $validated['jamaah_note'] ?? null,
                    'revision_number' => $revisionNumber,
                    'submitted_at' => now(),
                ]);

                $document->forceFill([
                    'file_path' => $newFilePath,
                    'status' => 'menunggu_verifikasi',
                    'admin_note' => null,
                ])->save();

                /*
                 * Kalau ingin file lama dihapus dari storage, aktifkan kode ini.
                 * Saya biarkan nonaktif agar file lama tetap aman untuk riwayat.
                 */
                // if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                //     Storage::disk('public')->delete($oldFilePath);
                // }
            }
        });

        return back()->with('success', 'Revisi dokumen berhasil dikirim. Admin akan melakukan pengecekan ulang.');
    }

    private function normalizeStatus(?string $status): string
    {
        $status = strtolower(trim((string) $status));

        return match ($status) {
            'perlu revisi', 'perlu_revisi', 'revision', 'revisi' => 'perlu_revisi',
            'ditolak', 'rejected', 'tolak' => 'ditolak',
            'valid', 'approved' => 'valid',
            'menunggu verifikasi', 'menunggu_verifikasi', 'pending', 'waiting' => 'menunggu_verifikasi',
            default => $status,
        };
    }

    private function getStatusLabel(?string $status): string
    {
        return match ($this->normalizeStatus($status)) {
            'valid' => 'Valid',
            'perlu_revisi' => 'Perlu Revisi',
            'ditolak' => 'Ditolak',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            default => $status ?: 'Menunggu Verifikasi',
        };
    }

    private function getDocumentLabel(?string $type): string
    {
        return match ($type) {
            'ktp' => 'KTP / Identitas',
            'paspor' => 'Paspor',
            'kartu_keluarga' => 'Kartu Keluarga',
            'buku_nikah_akta_ijazah' => 'Buku Nikah / Akta / Ijazah',
            'pas_foto' => 'Pas Foto',
            'sertifikat_vaksin' => 'Sertifikat Vaksin',
            'dokumen_tambahan' => 'Dokumen Tambahan',
            default => 'Dokumen',
        };
    }
}