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
        Schema::table('content', function (Blueprint $table) {
            // Add new SEO fields
            $table->text('content_meta_description')->nullable()->after('content_meta_keywords');
            $table->string('og_title', 500)->nullable()->after('content_meta_description');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('og_image')->nullable()->after('og_description');
            $table->string('og_type', 50)->nullable()->after('og_image');
            $table->string('twitter_title', 500)->nullable()->after('og_type');
            $table->text('twitter_description')->nullable()->after('twitter_title');
            $table->string('twitter_image')->nullable()->after('twitter_description');
            $table->string('twitter_card', 50)->nullable()->after('twitter_image');
            $table->string('canonical_url', 1000)->nullable()->after('twitter_card');
            $table->string('robots_meta', 100)->nullable()->after('canonical_url');
            $table->decimal('sitemap_priority', 2, 1)->default(0.5)->after('robots_meta');
            $table->string('sitemap_changefreq', 20)->nullable()->after('sitemap_priority');
            $table->boolean('exclude_from_sitemap')->default(false)->after('sitemap_changefreq');
        });

        // Add indexes for SEO fields
        Schema::table('content', function (Blueprint $table) {
            $table->index('exclude_from_sitemap', 'idx_content_exclude_sitemap');
            $table->index(['is_active', 'exclude_from_sitemap'], 'idx_content_active_sitemap');
        });
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
