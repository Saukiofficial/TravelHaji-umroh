<?php

use App\Models\DepartureGroup;
use App\Models\Registration;
use App\Models\RegistrationParticipant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_group_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(DepartureGroup::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Registration::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignIdFor(RegistrationParticipant::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('manifest_number')->nullable();

            $table->string('baggage_number')->nullable();
            $table->string('bus_number')->nullable();
            $table->string('room_number')->nullable();

            $table->enum('room_type', [
                'single',
                'double',
                'triple',
                'quad',
                'family',
            ])->nullable();

            $table->enum('visa_status', [
                'belum_diajukan',
                'proses',
                'terbit',
                'ditolak',
            ])->default('belum_diajukan');

            $table->string('visa_number')->nullable();
            $table->date('visa_issued_at')->nullable();

            $table->enum('ticket_status', [
                'belum_dipesan',
                'proses',
                'issued',
                'cancelled',
            ])->default('belum_dipesan');

            $table->string('ticket_number')->nullable();
            $table->string('booking_code')->nullable();

            $table->enum('departure_status', [
                'terdaftar',
                'siap_berangkat',
                'berangkat',
                'selesai',
                'batal',
            ])->default('terdaftar');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'departure_group_id',
                'registration_participant_id',
            ], 'group_participant_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_group_participants');
    }
};