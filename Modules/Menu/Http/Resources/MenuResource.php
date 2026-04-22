<?php

declare(strict_types=1);

namespace Modules\Menu\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Menu\Models\Menu;

/**
 * @mixin Menu
 */
final class MenuResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'item_type' => $this->item_type,
            'parent_id' => $this->parent_id !== null ? (int) $this->parent_id : null,
            'content_id' => $this->content_id !== null ? (int) $this->content_id : null,
            'categories_id' => $this->categories_id !== null ? (int) $this->categories_id : null,
            'position' => $this->position !== null ? (int) $this->position : null,
            'is_active' => $this->is_active !== null ? (bool) $this->is_active : null,
            'description' => $this->description,
            'url' => $this->url,
            'url_target' => $this->url_target,
            'size' => $this->size,
            'default_image' => $this->default_image,
            'rollover_image' => $this->rollover_image,
            'enable_mega_menu' => $this->enable_mega_menu !== null ? (bool) $this->enable_mega_menu : null,
            'menu_item_template' => $this->menu_item_template,
            'mega_menu_settings' => $this->mega_menu_settings,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
