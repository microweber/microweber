<?php

namespace MicroweberPackages\CdnSync\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $rel_type
 * @property int $rel_id
 * @property string $local_path
 * @property string $cdn_path
 * @property string|null $cdn_url
 * @property string $disk
 * @property string|null $bucket
 * @property string|null $etag
 * @property string|null $content_type
 * @property int|null $file_size
 * @property string|null $file_hash
 * @property bool $is_synced
 * @property \Carbon\Carbon|null $synced_at
 * @property array<mixed>|null $metadata
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class CdnSyncLog extends Model
{
    protected $table = 'cdn_sync_log';

    protected $fillable = [
        'rel_type',
        'rel_id',
        'local_path',
        'cdn_path',
        'cdn_url',
        'disk',
        'bucket',
        'etag',
        'content_type',
        'file_size',
        'file_hash',
        'is_synced',
        'synced_at',
        'metadata',
    ];

    protected $casts = [
        'is_synced' => 'boolean',
        'synced_at' => 'datetime',
        'metadata' => 'json',
        'file_size' => 'integer',
    ];

    /**
     * Get the owning syncable model (polymorphic).
     */
    public function syncable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('syncable', 'rel_type', 'rel_id');
    }

    /**
     * Scope to filter synced entries.
     */
    public function scopeSynced($query)
    {
        return $query->where('is_synced', true);
    }

    /**
     * Scope to filter by rel_type.
     */
    public function scopeForType($query, string $relType)
    {
        return $query->where('rel_type', $relType);
    }

    /**
     * Scope to filter by rel_type and rel_id.
     */
    public function scopeForModel($query, string $relType, int|string $relId)
    {
        return $query->where('rel_type', $relType)->where('rel_id', $relId);
    }
}