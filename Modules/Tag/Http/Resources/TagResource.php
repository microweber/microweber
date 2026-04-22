<?php

declare(strict_types=1);

namespace Modules\Tag\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Tag\Models\Tag;

/**
 * @mixin Tag
 */
final class TagResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'suggest' => (bool) $this->suggest,
            'count' => (int) $this->count,
            'tag_group_id' => $this->tag_group_id,
            'locale' => $this->locale,
        ];
    }
}
