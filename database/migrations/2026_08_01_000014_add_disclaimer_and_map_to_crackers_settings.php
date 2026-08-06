<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('crackers_settings')) {
            Schema::table('crackers_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('crackers_settings', 'company_slogan')) {
                    $table->string('company_slogan')->nullable()->after('support_hours');
                }
                if (!Schema::hasColumn('crackers_settings', 'license_number')) {
                    $table->string('license_number')->nullable()->after('company_slogan');
                }
                if (!Schema::hasColumn('crackers_settings', 'supreme_court_disclaimer')) {
                    $table->text('supreme_court_disclaimer')->nullable()->after('license_number');
                }
                if (!Schema::hasColumn('crackers_settings', 'google_map_embed')) {
                    $table->text('google_map_embed')->nullable()->after('supreme_court_disclaimer');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('crackers_settings')) {
            Schema::table('crackers_settings', function (Blueprint $table) {
                $table->dropColumn(['company_slogan', 'license_number', 'supreme_court_disclaimer', 'google_map_embed']);
            });
        }
    }
};
