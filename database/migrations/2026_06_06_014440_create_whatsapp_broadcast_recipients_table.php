<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_broadcast_recipients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('whatsapp_broadcast_id')
                ->constrained('whatsapp_broadcasts', 'id', 'wa_broadcast_rec_broadcast_fk')
                ->cascadeOnDelete();

            $table->foreignId('registration_id')
                ->nullable()
                ->constrained('registrations', 'id', 'wa_broadcast_rec_registration_fk')
                ->nullOnDelete();

            $table->foreignId('registration_participant_id')
                ->nullable()
                ->constrained('registration_participants', 'id', 'wa_broadcast_rec_participant_fk')
                ->nullOnDelete();

            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->text('final_message')->nullable();
            $table->string('wa_url')->nullable();
            $table->string('status')->default('ready');
            $table->timestamp('clicked_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('recipient_phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_broadcast_recipients');
    }
};