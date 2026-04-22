<?php

declare(strict_types=1);

namespace Modules\Media\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Media\Models\Media;

/**
 * @mixin Media
 */
final class MediaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $viewer = $request->user();
        $isAdmin = $viewer !== null && (int) $viewer->is_admin === 1;

        $data = [
            'id' => (int) $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'filename' => $this->filename,
            'url' => $this->url,
            'media_type' => $this->media_type,
            'file_type' => $this->file_type,
            'rel_type' => $this->rel_type,
            'rel_id' => $this->rel_id,
            'folder_id' => $this->folder_id !== null ? (int) $this->folder_id : null,
            'position' => $this->position !== null ? (int) $this->position : null,
            'file_size' => $this->file_size !== null ? (int) $this->file_size : null,
            'cdn_url' => $this->cdn_url,
            'cdn_provider' => $this->cdn_provider,
            'is_synced_to_cdn' => (bool) $this->is_synced_to_cdn,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];

        // Admins see full metadata + any upload trail (session_id, file_hash).
        if ($isAdmin) {
            $data['image_options'] = $this->image_options;
            $data['metadata'] = $this->metadata;
            $data['cdn_metadata'] = $this->cdn_metadata;
            $data['file_hash'] = $this->file_hash;
            $data['session_id'] = $this->session_id;
        }

        return $data;
    }
}
