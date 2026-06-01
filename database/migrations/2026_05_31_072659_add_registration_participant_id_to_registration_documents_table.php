<?php

use App\Models\RegistrationParticipant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_documents', function (Blueprint $table) {
            $table->foreignIdFor(RegistrationParticipant::class)
                ->nullable()
                ->after('registration_id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('registration_documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('registration_participant_id');
        });
    }
};