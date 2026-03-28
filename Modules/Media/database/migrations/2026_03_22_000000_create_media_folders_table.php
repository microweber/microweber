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
                    if (Schema::getConnection()->getDriverName() === 'sqlite') {
                        $table->unsignedBigInteger('folder_id')->nullable();
                    } else {
                        $table->foreignId('folder_id')->nullable()->after('id')->constrained('media_folders')->onDelete('set null');
                    }
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
            $table->unsignedInteger('created_by')->nullable();
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
                if (Schema::getConnection()->getDriverName() === 'sqlite') {
                    $table->unsignedBigInteger('folder_id')->nullable();
                } else {
                    $table->foreignId('folder_id')->nullable()->after('id')->constrained('media_folders')->onDelete('set null');
                }
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
                // SQLite rollback for this migration is intentionally a no-op.
                // Dropping a foreign-key column can require full table rebuilds
                // and may fail depending on SQLite version and pragma state.
                return;
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
