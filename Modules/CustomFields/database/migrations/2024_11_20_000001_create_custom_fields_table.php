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
        if (Schema::hasTable('custom_fields')) {
            return;
        }


        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('rel_type')->nullable();
            $table->text('rel_id')->nullable();
            $table->integer('position')->nullable();
            $table->string('type')->nullable();
            $table->text('name')->nullable();
            $table->text('name_key')->nullable();
            $table->text('placeholder')->nullable();
            $table->text('error_text')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->string('session_id')->nullable();
            $table->longText('options')->nullable();
            $table->integer('show_label')->nullable();
            $table->integer('is_active')->nullable();
            $table->integer('required')->nullable();
            $table->integer('copy_of_field')->nullable();

            $table->index(['rel_type', 'rel_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
