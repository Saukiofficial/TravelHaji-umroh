<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('registration_document_revisions');

        Schema::create('registration_document_revisions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('registration_id')->nullable();
            $table->unsignedBigInteger('registration_participant_id');

            $table->string('document_type');
            $table->string('document_label')->nullable();

            $table->string('old_file_path')->nullable();
            $table->string('new_file_path')->nullable();

            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();

            $table->text('admin_note')->nullable();
            $table->text('jamaah_note')->nullable();

            $table->unsignedInteger('revision_number')->default(1);

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->foreign('registration_id', 'reg_doc_rev_reg_id_fk')
                ->references('id')
                ->on('registrations')
                ->nullOnDelete();

            $table->foreign('registration_participant_id', 'reg_doc_rev_participant_id_fk')
                ->references('id')
                ->on('registration_participants')
                ->cascadeOnDelete();

            $table->index(['registration_id', 'registration_participant_id'], 'reg_doc_rev_reg_part_idx');
            $table->index('document_type', 'reg_doc_rev_doc_type_idx');
            $table->index('new_status', 'reg_doc_rev_new_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_document_revisions');
    }
};