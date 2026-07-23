<?php

namespace MicroweberPackages\MediaThumbnail\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Standalone MediaThumbnail model — no CMS dependencies.
 *
 * Stores cached thumbnail metadata so that a UUID-based route can
 * regenerate and serve thumbnails without recomputing image options
 * on every request.
 *
 * @property int         $id
 * @property string|null $uuid
 * @property string|null $filename
 * @property array<string, mixed>|null $image_options
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class MediaThumbnail extends Model
{
    use HasUuids;

    /** @var string */
    protected $table = 'media_thumbnails';

    /** @var list<string> */
    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'image_options' => 'json',
        ];
    }

    public function getKeyType(): string
    {
        return 'int';
    }

    public function getKeyName(): string
    {
        return 'id';
    }

    /**
     * @return list<string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model): void {
            if (empty($model->uuid)) {
                $model->uuid = $model->newUniqueId();
            }
        });
    }

    /**
     * Find a cached thumbnail item by filename (cache key).
     *
     * Uses a raw DB query for speed (avoids hydrating the model).
     *
     * @return array<string, mixed>|null
     */
    public static function findByFilename(string $filename): ?array
    {
        $row = DB::table('media_thumbnails')
            ->select(['id', 'filename', 'image_options', 'uuid'])
            ->where('filename', $filename)
            ->first();

        if (!$row) {
            return null;
        }

        /** @var array<string, mixed> $result */
        $result = (array) $row;

        if (isset($result['image_options']) && is_string($result['image_options'])) {
            $result['image_options'] = json_decode($result['image_options'], true);
        }

        return $result;
    }

    /**
     * Remove all thumbnail cache entries for a given filename (cache key).
     */
    public static function removeByFilename(string $filename): int
    {
        /** @var int $count */
        $count = static::where('filename', $filename)->delete();

        return $count;
    }
}