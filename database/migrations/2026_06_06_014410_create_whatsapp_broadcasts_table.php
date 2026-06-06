<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_broadcasts', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('message');
            $table->string('status')->default('draft');

            $table->unsignedInteger('total_recipients')->default(0);
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_broadcasts');
    }
};