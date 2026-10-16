<?php

namespace Modules\DigitalProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function downloads(): HasMany
    {
        return $this->hasMany(DigitalProductDownload::class, 'downloadable_file_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
