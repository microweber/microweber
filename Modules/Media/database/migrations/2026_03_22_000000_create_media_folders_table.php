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
        if (Schema::hasTable('media_folders')) {
            // Table exists, just ensure the folder_id column exists in media
            if (!Schema::hasColumn('media', 'folder_id')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->foreignId('folder_id')->nullable()->after('id')->constrained('media_folders')->onDelete('set null');
                    $table->index('folder_id');
                });
            } else {
                // Column exists, just add the index if needed
                if (!Schema::hasIndex('media', 'media_folder_id_index')) {
                    Schema::table('media', function (Blueprint $table) {
                        $table->index('folder_id');
                    });
                }
            }
            return;
        }

        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('media_folders')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_system')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('slug');
            $table->index('parent_id');
            $table->index('created_by');
        });

        // Add folder_id to media table
        if (!Schema::hasColumn('media', 'folder_id')) {
            Schema::table('media', function (Blueprint $table) {
                $table->foreignId('folder_id')->nullable()->after('id')->constrained('media_folders')->onDelete('set null');
                $table->index('folder_id');
            });
        } else {
            // Column exists, just add the foreign key constraint if not present
            Schema::table('media', function (Blueprint $table) {
                // Check if index exists before adding
                if (!Schema::hasIndex('media', 'media_folder_id_index')) {
                    $table->index('folder_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop folder_id from media table first (before dropping media_folders)
        if (Schema::hasColumn('media', 'folder_id')) {
            $driverName = Schema::getConnection()->getDriverName();

            if ($driverName === 'sqlite') {
                // SQLite doesn't support dropping foreign keys, just drop the column
                Schema::table('media', function (Blueprint $table) {
                    $table->dropColumn('folder_id');
                });
            } else {
                // MySQL/PostgreSQL: drop foreign key then column
                Schema::table('media', function (Blueprint $table) {
                    $table->dropForeign(['folder_id']);
                    $table->dropColumn('folder_id');
                });
            }
        }

        Schema::dropIfExists('media_folders');
    }
};
