<?php
namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\CdnSync\Contracts\CdnSyncable;
use MicroweberPackages\CdnSync\Traits\HasCdnSync;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;
use Modules\Media\Database\Factories\MediaFactory;
use MicroweberPackages\Url\Facades\UrlManager;
use MicroweberPackages\Format\Facades\Format;

class Media extends Model implements CdnSyncable
{
    //use \Conner\Tagging\Taggable;
    public $cacheTagsToClear = ['media','media_thumbnails'];

    use MaxPositionTrait;
    use CacheableQueryBuilderTrait;
    use HasFactory;
    use HasCdnSync;

    protected static function newFactory()
    {
        return MediaFactory::new();
    }

    public $table = 'media';

    protected $fillable = [
        'id',
        'folder_id',
        'title',
        'description',
        'rel_id',
        'rel_type',
        'media_type',
        'position',
        'filename',
        'session_id',
        'image_options',
        'metadata',
        'cdn_url',
        'cdn_provider',
        'cdn_metadata',
        'is_synced_to_cdn',
        'file_size',
        'file_hash',
    ];


    protected $casts = [
        'image_options' => 'json',
        'metadata' => 'json',
        'cdn_metadata' => 'json',
        'filename' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ => {SITE_URL}400/200/
        'is_synced_to_cdn' => 'boolean',
        'file_size' => 'integer',
    ];

    protected $attributes = [
        'media_type' => 'picture',
        'is_synced_to_cdn' => false,
    ];

    /**
     * Get the folder this media belongs to.
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    /**
     * Get the CDN URL if synced, otherwise return local URL.
     */
    public function getUrlAttribute(): string
    {
        if ($this->is_synced_to_cdn && $this->cdn_url) {
            return $this->cdn_url;
        }

        return $this->filename;
    }

    /**
     * Check if media is stored on CDN.
     */
    public function isOnCdn(): bool
    {
        return $this->is_synced_to_cdn && !empty($this->cdn_url);
    }

    /**
     * Get file type based on extension.
     */
    public function getFileTypeAttribute(): string
    {
        $ext = strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));

        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'tiff'];
        $videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'ogv'];
        $audioTypes = ['mp3', 'wav', 'ogg', 'flac', 'm4a', 'aac'];
        $documentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];

        if (in_array($ext, $imageTypes)) {
            return 'image';
        } elseif (in_array($ext, $videoTypes)) {
            return 'video';
        } elseif (in_array($ext, $audioTypes)) {
            return 'audio';
        } elseif (in_array($ext, $documentTypes)) {
            return 'document';
        }

        return 'file';
    }

    /**
     * Scope to filter by folder.
     */
    public function scopeInFolder($query, ?int $folderId = null)
    {
        if ($folderId === null) {
            return $query->whereNull('folder_id');
        }

        return $query->where('folder_id', $folderId);
    }

    /**
     * Scope to filter by CDN status.
     */
    public function scopeOnCdn($query)
    {
        return $query->where('is_synced_to_cdn', true)
                     ->whereNotNull('cdn_url');
    }

    /**
     * Scope to filter by file type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('media_type', $type);
    }

    /**
     * Return files to sync to CDN (CdnSyncable implementation).
     *
     * @return array<int, string>
     */
    public function getCdnSyncFiles(): array
    {
        if (!empty($this->filename)) {
            return [$this->filename];
        }
        return [];
    }

    /**
     * Return the CDN rel_type identifier (CdnSyncable implementation).
     */
    public function getCdnRelType(): string
    {
        return 'media';
    }

    /**
     * Return the CDN rel_id (CdnSyncable implementation).
     */
    public function getCdnRelId(): int|string
    {
        return $this->getKey();
    }

    /**
     * Get the first picture filename for a given rel_id and rel_type.
     */
    public static function queryPictureByRelIdAndRelType($relId, string $relType = 'content'): array
    {
        $getMedia = DB::table('media')
            ->select('filename')
            ->where('rel_type', $relType)
            ->where('rel_id', $relId)
            ->orderBy('position', 'ASC')
            ->first();

        if ($getMedia !== null) {
            $getMedia = (array) $getMedia;
            $surl = UrlManager::site();
            $getMedia['filename'] = Format::replace_once('{SITE_URL}', $surl, $getMedia['filename']);

            return $getMedia;
        }

        return [];
    }

}
