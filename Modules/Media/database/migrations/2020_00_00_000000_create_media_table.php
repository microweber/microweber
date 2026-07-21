<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('media')) {
            return;
        }


        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('filename')->nullable();
            $table->text('media_type')->nullable();

            // CDN sync fields — part of the canonical media schema so fresh installs and
            // test fixtures have them from the start. The incremental
            // add_cdn_fields_to_media_table migration (and the cdn-sync package's guarded
            // safety migration) backfill these on databases created before this.
            $table->string('cdn_url')->nullable();
            $table->string('cdn_provider')->nullable();
            $table->json('cdn_metadata')->nullable();
            $table->boolean('is_synced_to_cdn')->default(false);
            $table->bigInteger('file_size')->nullable();
            $table->string('file_hash')->nullable();

            $table->string('rel_type')->nullable();
            $table->string('rel_id')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->string('session_id')->nullable();

            $table->longText('image_options')->nullable();
            // metadata (alt text, etc.) — canonicalised here alongside the CDN fields so
            // fresh schemas match; add_metadata_to_media_table backfills older databases.
            $table->json('metadata')->nullable();
            $table->integer('position')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
};
