<?php

namespace Modules\Faq\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{

    protected $fillable = [
        'question',
        'answer',
        'position',
        'is_active',
        'rel_type',
        'rel_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer'
    ];

    public function scopeByRelation($query, $relType = null, $relId = null)
    {
        if ($relType && $relId) {
            return $query->where('rel_type', $relType)
                ->where('rel_id', $relId);
        }

        return $query->whereNull('rel_type')
            ->whereNull('rel_id');
    }
}
