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
        if (!Schema::hasTable('content')) {
            return;
        }

        // Only add columns that don't already exist
        $columnsToAdd = [
            'content_meta_description' => fn(Blueprint $t) => $t->text('content_meta_description')->nullable(),
            'og_title' => fn(Blueprint $t) => $t->string('og_title', 500)->nullable(),
            'og_description' => fn(Blueprint $t) => $t->text('og_description')->nullable(),
            'og_image' => fn(Blueprint $t) => $t->string('og_image')->nullable(),
            'og_type' => fn(Blueprint $t) => $t->string('og_type', 50)->nullable(),
            'twitter_title' => fn(Blueprint $t) => $t->string('twitter_title', 500)->nullable(),
            'twitter_description' => fn(Blueprint $t) => $t->text('twitter_description')->nullable(),
            'twitter_image' => fn(Blueprint $t) => $t->string('twitter_image')->nullable(),
            'twitter_card' => fn(Blueprint $t) => $t->string('twitter_card', 50)->nullable(),
            'canonical_url' => fn(Blueprint $t) => $t->string('canonical_url', 1000)->nullable(),
            'robots_meta' => fn(Blueprint $t) => $t->string('robots_meta', 100)->nullable(),
            'sitemap_priority' => fn(Blueprint $t) => $t->decimal('sitemap_priority', 2, 1)->default(0.5),
            'sitemap_changefreq' => fn(Blueprint $t) => $t->string('sitemap_changefreq', 20)->nullable(),
            'exclude_from_sitemap' => fn(Blueprint $t) => $t->boolean('exclude_from_sitemap')->default(false),
        ];

        Schema::table('content', function (Blueprint $table) use ($columnsToAdd) {
            foreach ($columnsToAdd as $col => $definition) {
                if (!Schema::hasColumn('content', $col)) {
                    $definition($table);
                }
            }
        });

        // Add indexes for SEO fields
        try {
            Schema::table('content', function (Blueprint $table) {
                if (!Schema::hasIndex('content', 'idx_content_exclude_sitemap')) {
                    $table->index('exclude_from_sitemap', 'idx_content_exclude_sitemap');
                }
                if (!Schema::hasIndex('content', 'idx_content_active_sitemap')) {
                    $table->index(['is_active', 'exclude_from_sitemap'], 'idx_content_active_sitemap');
                }
            });
        } catch (\Throwable $e) {
            // Indexes may already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content', function (Blueprint $table) {
            $table->dropIndex('idx_content_exclude_sitemap');
            $table->dropIndex('idx_content_active_sitemap');
            
            $table->dropColumn([
                'content_meta_description',
                'og_title',
                'og_description',
                'og_image',
                'og_type',
                'twitter_title',
                'twitter_description',
                'twitter_image',
                'twitter_card',
                'canonical_url',
                'robots_meta',
                'sitemap_priority',
                'sitemap_changefreq',
                'exclude_from_sitemap',
            ]);
        });
    }
};
