<?php

use App\Models\Package;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Package::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('code')->nullable()->unique();

            $table->enum('type', [
                'umroh',
                'haji',
            ])->default('umroh');

            $table->enum('status', [
                'draft',
                'open',
                'full',
                'departed',
                'completed',
                'cancelled',
            ])->default('draft');

            $table->date('departure_date')->nullable();
            $table->date('return_date')->nullable();

            $table->string('departure_airport')->nullable();
            $table->string('arrival_airport')->nullable();

            $table->string('airline')->nullable();
            $table->string('departure_flight_number')->nullable();
            $table->string('return_flight_number')->nullable();

            $table->dateTime('departure_time')->nullable();
            $table->dateTime('return_time')->nullable();

            $table->string('makkah_hotel')->nullable();
            $table->string('madinah_hotel')->nullable();

            $table->string('tour_leader_name')->nullable();
            $table->string('tour_leader_phone', 30)->nullable();

            $table->string('muthawif_name')->nullable();
            $table->string('muthawif_phone', 30)->nullable();

            $table->unsignedInteger('seat_quota')->default(0);
            $table->text('meeting_point')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_groups');
    }
};