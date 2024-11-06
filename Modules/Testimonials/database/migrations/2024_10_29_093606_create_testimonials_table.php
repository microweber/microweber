<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(Schema::hasTable('testimonials')) {
            return;
        }

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->longText('content')->nullable();
            $table->string('client_company')->nullable();
            $table->string('client_role')->nullable();
            $table->string('client_website')->nullable();
            $table->integer('position')->nullable();
            $table->string('client_image')->nullable();
            $table->string('rel_type')->nullable();
            $table->string('rel_id')->nullable();
            $table->longText('settings')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
