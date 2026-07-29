<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('template_fonts')) {
            return;
        }

        Schema::create('template_fonts', function (Blueprint $table) {
            $table->id();
            $table->string('family');
            $table->string('provider', 32)->default('google'); // google|custom|system
            $table->string('category', 64)->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->string('file_path')->nullable();
            $table->string('file_url')->nullable();
            $table->string('css_path')->nullable();
            $table->string('css_url')->nullable();
            $table->json('meta')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['family', 'provider']);
            $table->index(['is_enabled']);
            $table->index(['provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_fonts');
    }
};
