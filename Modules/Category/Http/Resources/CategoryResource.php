<?php

declare(strict_types=1);

namespace Modules\Category\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Models\Category;

/**
 * @mixin Category
 */
final class CategoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'parent_id' => (int) $this->parent_id,
            'title' => $this->title,
            'url' => $this->url,
            'description' => $this->description,
            'content' => $this->content,
            'rel_type' => $this->rel_type,
            'rel_id' => $this->rel_id !== null ? (int) $this->rel_id : null,
            'data_type' => $this->data_type,
            'position' => (int) $this->position,
            'is_active' => (bool) $this->is_active,
            'is_hidden' => (bool) $this->is_hidden,
            'is_deleted' => (bool) $this->is_deleted,
            'category_meta_title' => $this->category_meta_title,
            'category_meta_description' => $this->category_meta_description,
            'category_meta_keywords' => $this->category_meta_keywords,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
