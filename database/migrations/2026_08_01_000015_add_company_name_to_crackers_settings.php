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
                if (!Schema::hasColumn('crackers_settings', 'company_name')) {
                    $table->string('company_name')->default('S.R. TRADERS')->after('id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('crackers_settings')) {
            Schema::table('crackers_settings', function (Blueprint $table) {
                if (Schema::hasColumn('crackers_settings', 'company_name')) {
                    $table->dropColumn('company_name');
                }
            });
        }
    }
};
