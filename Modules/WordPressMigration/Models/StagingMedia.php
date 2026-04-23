<?php

namespace Modules\WordPressMigration\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Staged media record — the rehoster's note-to-self of which
 * URLs it would fetch if the dry-run were committed. Lives in
 * `wp_migration_staging_media`.
 *
 * Role values are free-form so we can add new ones (gallery,
 * srcset, etc.) without a migration:
 *   - `featured` — WP featured_media URL
 *   - `inline`   — `<img>`/`<a>` encountered in post HTML
 *
 * @property int $id
 * @property int $job_id
 * @property int|null $staging_content_id
 * @property string $source_url
 * @property string $role
 * @property string $source_url_hash
 * @property bool $excluded
 */
class StagingMedia extends Model
{
    protected $table = 'wp_migration_staging_media';

    protected $fillable = [
        'job_id',
        'staging_content_id',
        'source_url',
        'role',
        'source_url_hash',
        'excluded',
    ];

    protected $casts = [
        'job_id' => 'integer',
        'staging_content_id' => 'integer',
        'excluded' => 'boolean',
    ];
}
