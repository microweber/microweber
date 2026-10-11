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
        Schema::create('module_dependencies', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->string('dependency_module_name');
            $table->string('version_constraint')->nullable()->comment('e.g., ^1.0, >=2.0, ~3.0');
            $table->enum('dependency_type', ['require', 'conflict', 'suggest', 'replace'])->default('require');
            $table->boolean('is_optional')->default(false)->comment('For soft dependencies');
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['module_name', 'dependency_type']);
            $table->index(['dependency_module_name', 'dependency_type']);
            $table->unique(['module_name', 'dependency_module_name', 'dependency_type'], 'unique_module_dependency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_dependencies');
    }
};
