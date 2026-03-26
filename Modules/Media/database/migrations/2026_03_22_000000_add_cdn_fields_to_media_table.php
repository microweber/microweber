<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('media')) {
            return;
        }

        Schema::table('media', function (Blueprint $table) {
            if (!Schema::hasColumn('media', 'cdn_url')) {
                $table->string('cdn_url')->nullable()->after('filename');
            }
            if (!Schema::hasColumn('media', 'cdn_provider')) {
                $table->string('cdn_provider')->nullable()->after('cdn_url');
            }
            if (!Schema::hasColumn('media', 'cdn_metadata')) {
                $table->json('cdn_metadata')->nullable()->after('cdn_provider');
            }
            if (!Schema::hasColumn('media', 'is_synced_to_cdn')) {
                $table->boolean('is_synced_to_cdn')->default(false)->after('cdn_metadata');
            }
            if (!Schema::hasColumn('media', 'file_size')) {
                $table->bigInteger('file_size')->nullable()->after('is_synced_to_cdn');
            }
            if (!Schema::hasColumn('media', 'file_hash')) {
                $table->string('file_hash')->nullable()->after('file_size');
            }
            // folder_id is managed by create_media_folders_table migration
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('media')) {
            return;
        }

        Schema::table('media', function (Blueprint $table) {
            // folder_id is managed by create_media_folders_table migration
            $table->dropColumn([
                'cdn_url',
                'cdn_provider',
                'cdn_metadata',
                'is_synced_to_cdn',
                'file_size',
                'file_hash',
            ]);
        });
    }
};
