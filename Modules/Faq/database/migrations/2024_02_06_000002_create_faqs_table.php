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
        if(Schema::hasTable('faqs')) {
            return;
        }

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question')->nullable();
            $table->text('answer')->nullable();
            $table->integer('position')->default(0)->nullable();
            $table->integer('is_active')->default(1)->nullable();
            $table->string('rel_type', 500)->nullable();
            $table->string('rel_id', 500)->nullable();
            $table->timestamps();

            $table->index(['rel_type', 'rel_id']);
        });
    }


};
