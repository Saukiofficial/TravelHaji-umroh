<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->enum('type', ['umroh', 'haji'])->default('umroh');
            $table->string('title');
            $table->string('slug')->unique();

            $table->decimal('price', 15, 2)->nullable();

            $table->integer('duration_days')->nullable();
            $table->date('departure_date')->nullable();

            $table->string('airline')->nullable();
            $table->string('makkah_hotel')->nullable();
            $table->string('madinah_hotel')->nullable();

            $table->integer('seat')->nullable();

            $table->longText('facilities')->nullable();
            $table->longText('itinerary')->nullable();
            $table->longText('requirements')->nullable();
            $table->longText('description')->nullable();

            $table->string('image')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published'])->default('published');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};