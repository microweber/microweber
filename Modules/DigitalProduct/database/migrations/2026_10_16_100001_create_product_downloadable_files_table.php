<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_downloadable_files')) {
            return;
        }

        Schema::create('product_downloadable_files', function (Blueprint $table) {
            $table->id();

            // Products are Content rows (Product extends Content).
            $table->foreignId('product_id')->constrained('content')->onDelete('cascade');

            // Where the file lives. disk = a Laravel filesystem disk name (S3 or
            // local userfiles disk, resolved via userfiles_disk() at upload time);
            // file_path = the path within that disk. No public URL is ever exposed.
            $table->string('disk')->nullable();
            $table->string('file_path', 1024);
            $table->string('original_name');
            $table->unsignedBigInteger('size')->nullable();
            $table->string('mime')->nullable();

            // Per-file override of the global default (NULL = unlimited downloads).
            $table->unsignedInteger('download_limit')->nullable();

            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('product_id');
            $table->index(['product_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_downloadable_files');
    }
};
