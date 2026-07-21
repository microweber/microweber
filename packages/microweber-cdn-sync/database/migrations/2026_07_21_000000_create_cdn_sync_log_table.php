<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cdn_sync_log')) {
            return;
        }

        Schema::create('cdn_sync_log', function (Blueprint $table) {
            $table->id();
            $table->string('rel_type')->index();
            $table->unsignedBigInteger('rel_id')->index();
            $table->string('local_path');
            $table->string('cdn_path');
            $table->string('cdn_url')->nullable();
            $table->string('disk')->default('cdn');
            $table->string('bucket')->nullable();
            $table->string('etag')->nullable();
            $table->string('content_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('file_hash')->nullable();
            $table->boolean('is_synced')->default(false);
            $table->timestamp('synced_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['rel_type', 'rel_id']);
            $table->index(['is_synced']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cdn_sync_log');
    }
};