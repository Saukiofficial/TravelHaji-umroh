<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => ['nullable', 'exists:packages,id'],

            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'total_participants' => ['required', 'integer', 'min:1', 'max:20'],
            'note' => ['nullable', 'string'],

            'participants' => ['required', 'array', 'min:1'],
            'participants.*.order_number' => ['required', 'integer', 'min:1'],
            'participants.*.name' => ['required', 'string', 'max:255'],
            'participants.*.gender' => ['nullable', 'in:Laki-laki,Perempuan'],
            'participants.*.birth_place' => ['nullable', 'string', 'max:255'],
            'participants.*.birth_date' => ['nullable', 'date'],
            'participants.*.phone' => ['nullable', 'string', 'max:30'],
            'participants.*.email' => ['nullable', 'email', 'max:255'],
            'participants.*.nik' => ['nullable', 'string', 'max:30'],
            'participants.*.passport_number' => ['nullable', 'string', 'max:100'],
            'participants.*.passport_issued_at' => ['nullable', 'date'],
            'participants.*.passport_expired_at' => ['nullable', 'date'],
            'participants.*.address' => ['nullable', 'string'],
            'participants.*.emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'participants.*.emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'participants.*.health_note' => ['nullable', 'string'],
            'participants.*.note' => ['nullable', 'string'],

            'participants.*.documents' => ['nullable', 'array'],
            'participants.*.documents.ktp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'participants.*.documents.paspor' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'participants.*.documents.kartu_keluarga' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'participants.*.documents.buku_nikah_akta_ijazah' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'participants.*.documents.pas_foto' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'participants.*.documents.sertifikat_vaksin' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'participants.*.documents.dokumen_tambahan' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $participantsData = $validated['participants'];

            $registration = Registration::query()->create([
                'package_id' => $validated['package_id'] ?? null,
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
                'total_participants' => count($participantsData),
                'note' => $validated['note'] ?? null,
                'status' => 'baru',
            ]);

            $documentTypes = array_keys(RegistrationDocument::documentTypeOptions());

            foreach ($participantsData as $index => $participantData) {
                $participant = $registration->participants()->create([
                    'order_number' => $index + 1,
                    'name' => $participantData['name'],
                    'gender' => $participantData['gender'] ?? null,
                    'birth_place' => $participantData['birth_place'] ?? null,
                    'birth_date' => $participantData['birth_date'] ?? null,
                    'phone' => $participantData['phone'] ?? null,
                    'email' => $participantData['email'] ?? null,
                    'nik' => $participantData['nik'] ?? null,
                    'passport_number' => $participantData['passport_number'] ?? null,
                    'passport_issued_at' => $participantData['passport_issued_at'] ?? null,
                    'passport_expired_at' => $participantData['passport_expired_at'] ?? null,
                    'address' => $participantData['address'] ?? null,
                    'emergency_contact_name' => $participantData['emergency_contact_name'] ?? null,
                    'emergency_contact_phone' => $participantData['emergency_contact_phone'] ?? null,
                    'health_note' => $participantData['health_note'] ?? null,
                    'note' => $participantData['note'] ?? null,
                ]);

                foreach ($documentTypes as $documentType) {
                    $inputName = "participants.$index.documents.$documentType";

                    if (! $request->hasFile($inputName)) {
                        continue;
                    }

                    $path = $request
                        ->file($inputName)
                        ->store('registration-documents', 'public');

                    $participant->documents()->create([
                        'registration_id' => $registration->id,
                        'document_type' => $documentType,
                        'file_path' => $path,
                        'status' => 'belum_dicek',
                        'note' => null,
                        'verified_at' => null,
                    ]);
                }
            }
        });

        return Redirect::back()->with(
            'success',
            'Pendaftaran berhasil dikirim. Admin kami akan segera menghubungi Anda melalui WhatsApp.'
        );
    }
}