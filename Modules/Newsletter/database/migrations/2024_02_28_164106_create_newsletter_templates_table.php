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
        if (!Schema::hasTable('newsletter_templates')) {
            Schema::create('newsletter_templates', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->longText('text')->nullable();
                $table->longText('json')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_templates');
    }
};
