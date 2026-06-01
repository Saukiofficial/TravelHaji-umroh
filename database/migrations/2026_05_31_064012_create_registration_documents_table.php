<?php

use App\Models\Registration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Registration::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('document_type');
            $table->string('file_path')->nullable();

            $table->enum('status', [
                'belum_dicek',
                'valid',
                'perlu_revisi',
                'ditolak',
            ])->default('belum_dicek');

            $table->text('note')->nullable();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_documents');
    }
};