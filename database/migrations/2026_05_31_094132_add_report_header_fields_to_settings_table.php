<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'report_brand_name')) {
                $table->string('report_brand_name')->nullable();
            }

            if (! Schema::hasColumn('settings', 'report_brand_tagline')) {
                $table->string('report_brand_tagline')->nullable();
            }

            if (! Schema::hasColumn('settings', 'report_address')) {
                $table->text('report_address')->nullable();
            }

            if (! Schema::hasColumn('settings', 'report_phone')) {
                $table->string('report_phone')->nullable();
            }

            if (! Schema::hasColumn('settings', 'report_email')) {
                $table->string('report_email')->nullable();
            }

            if (! Schema::hasColumn('settings', 'report_logo')) {
                $table->string('report_logo')->nullable();
            }

            if (! Schema::hasColumn('settings', 'report_header_color')) {
                $table->string('report_header_color')->default('004F41');
            }

            if (! Schema::hasColumn('settings', 'report_accent_color')) {
                $table->string('report_accent_color')->default('E8BD62');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'report_brand_name',
                'report_brand_tagline',
                'report_address',
                'report_phone',
                'report_email',
                'report_logo',
                'report_header_color',
                'report_accent_color',
            ]);
        });
    }
};