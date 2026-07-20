<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('content_fields')) {
            Schema::create('content_fields', function (Blueprint $table) {
                $table->id();
                $table->string('rel_type')->default('');
                $table->string('rel_id')->default('');
                $table->text('field')->nullable();
                $table->longText('value')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('edited_by')->nullable();
                $table->timestamps();

                $table->index(['rel_type', 'rel_id']);
            });
        }

        if (!Schema::hasTable('content_fields_drafts')) {
            Schema::create('content_fields_drafts', function (Blueprint $table) {
                $table->id();
                $table->string('rel_type')->default('');
                $table->string('rel_id')->default('');
                $table->text('field')->nullable();
                $table->longText('value')->nullable();
                $table->string('session_id')->nullable();
                $table->integer('is_temp')->nullable();
                $table->longText('url')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('edited_by')->nullable();
                $table->timestamps();

                $table->index(['rel_type', 'rel_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('content_fields');
        Schema::dropIfExists('content_fields_drafts');
    }
};
