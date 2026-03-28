<?php

declare(strict_types=1);

namespace Modules\Content\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Content\Models\Content;

/**
 * @mixin Content
 */
final class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title !== null ? strip_tags((string) $this->title) : null,
            'url' => $this->url,
            'content_type' => $this->content_type,
            'subtype' => $this->subtype,
            'description' => $this->description,
            'content' => $this->content,
            'content_body' => $this->content_body,
            'content_meta_title' => $this->content_meta_title,
            'content_meta_keywords' => $this->content_meta_keywords,
            'content_meta_description' => $this->content_meta_description,
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_image' => $this->og_image,
            'og_type' => $this->og_type,
            'twitter_title' => $this->twitter_title,
            'twitter_description' => $this->twitter_description,
            'twitter_image' => $this->twitter_image,
            'twitter_card' => $this->twitter_card,
            'canonical_url' => $this->canonical_url,
            'robots_meta' => $this->robots_meta,
            'sitemap_priority' => $this->sitemap_priority,
            'sitemap_changefreq' => $this->sitemap_changefreq,
            'exclude_from_sitemap' => $this->exclude_from_sitemap,
            'is_home' => $this->is_home,
            'is_shop' => $this->is_shop,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
            'require_login' => $this->require_login,
            'parent' => $this->parent,
            'layout_file' => $this->layout_file,
            'active_site_template' => $this->active_site_template,
            'position' => $this->position,
            'original_link' => $this->original_link,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'link' => $this->link,
            'image' => $this->image,
            'edit_link' => $this->editLink(),
            'live_edit_link' => $this->liveEditLink(),
            'categories' => $this->whenLoaded('categories'),
            'tags' => $this->whenLoaded('tags'),
            'media' => $this->whenLoaded('media'),
            'custom_fields' => $this->whenLoaded('customField'),
        ];
    }
}
