<?php

namespace Modules\WordPressMigration\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Staged content row — what would land on `content` if the dry-run
 * were committed. Lives in `wp_migration_staging_content` and is
 * scoped per-job via {@see $job_id}.
 *
 * Columns `categories` / `tags` are JSON arrays of free-form
 * name strings (not slugs) — the taxonomy resolver does its
 * own lookup at Commit time against the current local taxonomy
 * state, so storing names keeps the preview decoupled from a
 * specific TaxonomyIndex priming run.
 *
 * @property int $id
 * @property int $job_id
 * @property string $import_source
 * @property string $source_guid
 * @property string|null $title
 * @property string|null $content_html
 * @property string|null $excerpt
 * @property string|null $canonical_url
 * @property string|null $source_host
 * @property string|null $featured_image_url
 * @property string|null $author_slug
 * @property \Illuminate\Support\Carbon|null $posted_at
 * @property array|null $categories
 * @property array|null $tags
 * @property bool $excluded
 */
class StagingContent extends Model
{
    protected $table = 'wp_migration_staging_content';

    protected $fillable = [
        'job_id',
        'import_source',
        'source_guid',
        'title',
        'content_html',
        'excerpt',
        'canonical_url',
        'source_host',
        'featured_image_url',
        'author_slug',
        'posted_at',
        'categories',
        'tags',
        'excluded',
    ];

    protected $casts = [
        'job_id' => 'integer',
        'excluded' => 'boolean',
        'posted_at' => 'datetime',
        'categories' => 'array',
        'tags' => 'array',
    ];
}
