<?php

use App\Models\Registration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Registration::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('order_number')->default(1);

            $table->string('name');
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();

            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();

            $table->string('nik')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_issued_at')->nullable();
            $table->date('passport_expired_at')->nullable();

            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 30)->nullable();

            $table->text('health_note')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_participants');
    }
};