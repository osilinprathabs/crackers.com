<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crackers_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('crackers_settings', 'support_phone')) {
                $table->string('support_phone')->nullable()->default('+91 98765 43210');
            }
            if (!Schema::hasColumn('crackers_settings', 'support_email')) {
                $table->string('support_email')->nullable()->default('support@crackers.com');
            }
            if (!Schema::hasColumn('crackers_settings', 'support_address')) {
                $table->text('support_address')->nullable();
            }
            if (!Schema::hasColumn('crackers_settings', 'support_hours')) {
                $table->string('support_hours')->nullable()->default('Mon - Sun: 8:00 AM - 10:00 PM');
            }
            if (!Schema::hasColumn('crackers_settings', 'terms_and_conditions')) {
                $table->longText('terms_and_conditions')->nullable();
            }
            if (!Schema::hasColumn('crackers_settings', 'privacy_policy')) {
                $table->longText('privacy_policy')->nullable();
            }
            if (!Schema::hasColumn('crackers_settings', 'shipping_policy')) {
                $table->longText('shipping_policy')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('crackers_settings', function (Blueprint $table) {
            $table->dropColumn([
                'support_phone',
                'support_email',
                'support_address',
                'support_hours',
                'terms_and_conditions',
                'privacy_policy',
                'shipping_policy',
            ]);
        });
    }
};
