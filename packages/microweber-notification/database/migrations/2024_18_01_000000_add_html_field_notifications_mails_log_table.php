<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = 'notifications_mails_log';

        if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'html')) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->longText('html')->nullable();
            });
        }
    }

    public function down(): void
    {
        $tableName = 'notifications_mails_log';

        if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'html')) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn('html');
            });
        }
    }
};
