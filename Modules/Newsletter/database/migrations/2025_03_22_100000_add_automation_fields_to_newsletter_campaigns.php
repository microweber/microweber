<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('newsletter_campaigns')) {
            Schema::table('newsletter_campaigns', function (Blueprint $table) {
                if (!Schema::hasColumn('newsletter_campaigns', 'campaign_type')) {
                    $table->string('campaign_type')->default('broadcast')->after('name')
                        ->comment('broadcast, triggered, automation');
                }
                if (!Schema::hasColumn('newsletter_campaigns', 'trigger_event')) {
                    $table->string('trigger_event')->nullable()->after('campaign_type')
                        ->comment('cart_abandoned, order_placed, user_registered, etc.');
                }
                if (!Schema::hasColumn('newsletter_campaigns', 'delay_minutes')) {
                    $table->integer('delay_minutes')->nullable()->default(0)->after('trigger_event');
                }
                if (!Schema::hasColumn('newsletter_campaigns', 'trigger_conditions')) {
                    $table->json('trigger_conditions')->nullable()->after('delay_minutes')
                        ->comment('JSON conditions for trigger matching');
                }
                if (!Schema::hasColumn('newsletter_campaigns', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('newsletter_campaigns')) {
            Schema::table('newsletter_campaigns', function (Blueprint $table) {
                $table->dropColumn([
                    'campaign_type',
                    'trigger_event',
                    'delay_minutes',
                    'trigger_conditions',
                    'is_active'
                ]);
            });
        }
    }
};
