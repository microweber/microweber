<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('resource_permissions')) {
            Schema::create('resource_permissions', function (Blueprint $table) {
                $table->id();
                $table->string('resource_name'); // e.g., 'users', 'content', 'products'
                $table->string('action'); // e.g., 'view', 'create', 'edit', 'delete'
                $table->string('permission_name'); // e.g., 'view users', 'create content'
                $table->string('description')->nullable();
                $table->string('category')->default('general'); // For grouping permissions
                $table->integer('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['resource_name', 'action']);
                $table->index('category');
            });
        }

        if (!Schema::hasTable('role_resource_permissions')) {
            Schema::create('role_resource_permissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('role_id');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreignId('resource_permission_id')->constrained('resource_permissions')->onDelete('cascade');
                $table->boolean('is_allowed')->default(true);
                $table->json('conditions')->nullable(); // JSON conditions for advanced access
                $table->timestamps();

                $table->unique(['role_id', 'resource_permission_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_resource_permissions');
        Schema::dropIfExists('resource_permissions');
    }
};
