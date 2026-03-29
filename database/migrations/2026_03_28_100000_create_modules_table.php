<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('modules')) {
            if (!Schema::hasColumn('modules', 'is_enabled')) {
                Schema::table('modules', function (Blueprint $table) {
                    $table->boolean('is_enabled')->default(true)->after('description');
                });
            }
            return;
        }

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('module')->nullable();
            $table->string('type')->nullable();
            $table->string('as_element')->nullable();
            $table->boolean('installed')->default(false);
            $table->string('ui')->nullable();
            $table->boolean('ui_admin')->nullable();
            $table->boolean('ui_admin_iframe')->nullable();
            $table->boolean('is_system')->default(false);
            $table->string('categories')->nullable();
            $table->json('settings')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
