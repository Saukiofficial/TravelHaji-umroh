<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_participants', function (Blueprint $table) {
            if (! Schema::hasColumn('registration_participants', 'revision_token')) {
                $table->string('revision_token')->nullable()->unique()->after('note');
            }

            if (! Schema::hasColumn('registration_participants', 'revision_token_created_at')) {
                $table->timestamp('revision_token_created_at')->nullable()->after('revision_token');
            }
        });

        DB::table('registration_participants')
            ->whereNull('revision_token')
            ->orderBy('id')
            ->chunkById(100, function ($participants) {
                foreach ($participants as $participant) {
                    DB::table('registration_participants')
                        ->where('id', $participant->id)
                        ->update([
                            'revision_token' => Str::random(64),
                            'revision_token_created_at' => now(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('registration_participants', function (Blueprint $table) {
            if (Schema::hasColumn('registration_participants', 'revision_token_created_at')) {
                $table->dropColumn('revision_token_created_at');
            }

            if (Schema::hasColumn('registration_participants', 'revision_token')) {
                $table->dropColumn('revision_token');
            }
        });
    }
};