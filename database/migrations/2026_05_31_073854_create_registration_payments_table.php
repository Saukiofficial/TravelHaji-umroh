<?php

use App\Models\Registration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Registration::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('payment_code')->nullable();

            $table->enum('payment_type', [
                'dp',
                'cicilan',
                'pelunasan',
                'tambahan',
                'refund',
            ])->default('dp');

            $table->decimal('amount', 15, 2)->default(0);

            $table->enum('payment_method', [
                'transfer_bank',
                'cash',
                'qris',
                'lainnya',
            ])->default('transfer_bank');

            $table->date('paid_at')->nullable();

            $table->string('proof_file')->nullable();

            $table->enum('status', [
                'menunggu_verifikasi',
                'valid',
                'ditolak',
            ])->default('menunggu_verifikasi');

            $table->text('note')->nullable();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_payments');
    }
};