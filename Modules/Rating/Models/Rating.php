<?php

namespace Modules\Rating\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class Rating extends Model
{
    use CacheableQueryBuilderTrait;

    protected $table = 'rating';

    protected $fillable = [
        'rel_type',
        'rel_id',
        'rating',
        'comment',
        'session_id',
        'created_by',
        'edited_by'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->edited_by = auth()->id();
            }
        });
    }

    public static function getAverage($relType, $relId)
    {
        $cacheKey = md5($relType . $relId . 'avg');
        return cache()->tags('rating')->remember($cacheKey, now()->addHour(), function () use ($relType, $relId) {
            return static::where('rel_type', $relType)
                ->where('rel_id', $relId)
                ->avg('rating') ?? 0;
        });
    }
}
