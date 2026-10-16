<?php

namespace Modules\DigitalProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Modules\DigitalProduct\Services\DigitalProductService;

/**
 * A downloadable file attached to a product (a Content row, content_type=product).
 */
class ProductDownloadableFile extends Model
{
    protected $table = 'product_downloadable_files';

    protected $fillable = [
        'product_id',
        'disk',
        'file_path',
        'original_name',
        'size',
        'mime',
        'download_limit',
        'position',
        'is_active',
    ];

    protected $casts = [
        'size' => 'integer',
        'download_limit' => 'integer',
        'position' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Backfill storage metadata so the admin form only needs the upload +
        // product. Only fills blanks, so explicit values (e.g. in tests or
        // programmatic use) are preserved.
        static::saving(function (ProductDownloadableFile $file) {
            if (empty($file->disk)) {
                $file->disk = (new DigitalProductService())->defaultDisk();
            }

            if ($file->file_path) {
                if (empty($file->original_name)) {
                    $file->original_name = basename($file->file_path);
                }

                if (empty($file->size) || empty($file->mime)) {
                    try {
                        $disk = Storage::disk($file->disk);
                        if ($disk->exists($file->file_path)) {
                            $file->size = $file->size ?: $disk->size($file->file_path);
                            $file->mime = $file->mime ?: $disk->mimeType($file->file_path);
                        }
                    } catch (\Throwable $e) {
                        // Non-fatal: metadata is best-effort.
                    }
                }
            }
        });
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(DigitalProductDownload::class, 'downloadable_file_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
