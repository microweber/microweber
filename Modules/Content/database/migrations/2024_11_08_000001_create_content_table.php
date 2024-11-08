<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('content')) {
            return;
        }

        Schema::create('content', function (Blueprint $table) {
            $table->id();
            $table->string('content_type')->nullable();
            $table->string('subtype')->nullable();
            $table->text('url')->nullable();
            $table->text('title')->nullable();
            $table->integer('parent')->nullable();
            $table->text('description')->nullable();
            $table->integer('position')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_body')->nullable();
            $table->integer('is_active')->default(1)->nullable();
            $table->string('subtype_value')->nullable();
            $table->string('custom_type')->nullable();
            $table->string('custom_type_value')->nullable();
            $table->string('active_site_template')->nullable();
            $table->string('layout_file')->nullable();
            $table->string('layout_name')->nullable();
            $table->string('layout_style')->nullable();
            $table->string('content_filename')->nullable();
            $table->string('original_link')->nullable();
            $table->integer('is_home')->default(0)->nullable();
            $table->integer('is_pinged')->default(0)->nullable();
            $table->integer('is_shop')->default(0)->nullable();
            $table->integer('is_deleted')->default(0)->nullable();
            $table->integer('require_login')->default(0)->nullable();
            $table->string('status')->nullable();
            $table->text('content_meta_title')->nullable();
            $table->text('content_meta_keywords')->nullable();
            $table->string('session_id')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->dateTime('posted_at')->nullable();
            $table->integer('draft_of')->nullable();
            $table->integer('copy_of')->nullable();

            //  $table->index(['url', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content');
    }
};
