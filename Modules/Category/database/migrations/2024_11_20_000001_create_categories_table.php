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
        if (Schema::hasTable('categories')) {
            return;
        }

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->string('data_type')->nullable();
            $table->text('title')->nullable();
            $table->longText('url')->nullable();
            $table->integer('parent_id')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('rel_type')->nullable();
            $table->integer('rel_id')->nullable();
            $table->integer('position')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->integer('is_hidden')->default(0);
            $table->integer('is_active ')->default(1);
            $table->integer('users_can_create_subcategories')->nullable();
            $table->integer('users_can_create_content')->nullable();
            $table->string('users_can_create_content_allowed_usergroups')->nullable();
            $table->text('category_meta_title')->nullable();
            $table->text('category_meta_keywords')->nullable();
            $table->text('category_meta_description')->nullable();
            $table->string('category_subtype')->nullable();
            $table->longText('category_subtype_settings')->nullable();

            $table->index(['rel_type', 'rel_id', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
