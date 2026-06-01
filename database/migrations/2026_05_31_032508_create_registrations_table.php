<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('package_id')
                ->nullable()
                ->constrained('packages')
                ->nullOnDelete();

            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();

            $table->text('address')->nullable();

            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->date('birth_date')->nullable();

            $table->integer('total_participants')->default(1);

            $table->text('note')->nullable();
            $table->string('document_file')->nullable();

            $table->enum('status', [
                'baru',
                'dihubungi',
                'proses',
                'selesai',
                'batal',
            ])->default('baru');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};