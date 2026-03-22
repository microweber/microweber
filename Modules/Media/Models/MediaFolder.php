<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class MediaFolder extends Model
{
    use CacheableQueryBuilderTrait;

    protected $table = 'media_folders';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'created_by',
        'is_system',
        'sort_order',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent folder.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the child folders.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get all media items in this folder.
     */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'folder_id');
    }

    /**
     * Get all media items including those in subfolders.
     */
    public function allMedia()
    {
        $mediaIds = $this->media()->pluck('id')->toArray();
        $childFolderIds = $this->getAllChildFolderIds();

        if (!empty($childFolderIds)) {
            $childMediaIds = Media::whereIn('folder_id', $childFolderIds)->pluck('id')->toArray();
            $mediaIds = array_merge($mediaIds, $childMediaIds);
        }

        return Media::whereIn('id', $mediaIds);
    }

    /**
     * Get all child folder IDs recursively.
     */
    public function getAllChildFolderIds(): array
    {
        $ids = [];
        $children = $this->children;

        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllChildFolderIds());
        }

        return $ids;
    }

    /**
     * Get the full path of the folder.
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            $path[] = $parent->name;
            $parent = $parent->parent;
        }

        return implode(' / ', array_reverse($path));
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($folder) {
            if (empty($folder->slug)) {
                $folder->slug = \Illuminate\Support\Str::slug($folder->name);
            }
        });

        static::updating(function ($folder) {
            if ($folder->isDirty('name') && empty($folder->slug)) {
                $folder->slug = \Illuminate\Support\Str::slug($folder->name);
            }
        });
    }
}
