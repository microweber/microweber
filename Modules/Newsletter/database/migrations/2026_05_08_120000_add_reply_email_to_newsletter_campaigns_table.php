<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('newsletter_campaigns')) {
            return;
        }

        if (! Schema::hasColumn('newsletter_campaigns', 'from_email')) {
            Schema::table('newsletter_campaigns', function (Blueprint $table) {
                $table->string('from_email')->nullable()->after('from_name');
            });
        }

        if (! Schema::hasColumn('newsletter_campaigns', 'reply_email')) {
            Schema::table('newsletter_campaigns', function (Blueprint $table) {
                $table->string('reply_email')->nullable()->after('from_email');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('newsletter_campaigns')) {
            return;
        }

        if (Schema::hasColumn('newsletter_campaigns', 'reply_email')) {
            Schema::table('newsletter_campaigns', function (Blueprint $table) {
                $table->dropColumn('reply_email');
            });
        }

        if (Schema::hasColumn('newsletter_campaigns', 'from_email')) {
            Schema::table('newsletter_campaigns', function (Blueprint $table) {
                $table->dropColumn('from_email');
            });
        }
    }
};
