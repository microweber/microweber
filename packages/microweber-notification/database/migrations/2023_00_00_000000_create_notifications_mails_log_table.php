<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifications_mails_log')) {
            return;
        }

        Schema::create('notifications_mails_log', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('type');
            $table->string('notifiable_type');
            $table->string('notifiable_id');
            $table->longText('html')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_mails_log');
    }
};
