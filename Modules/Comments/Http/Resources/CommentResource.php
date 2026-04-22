<?php

declare(strict_types=1);

namespace Modules\Comments\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Comments\Models\Comment;

/**
 * @mixin Comment
 */
final class CommentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $viewer = $request->user();
        $isAdmin = $viewer !== null && (int) $viewer->is_admin === 1;

        // Bypass the Comment model's mutators (they inject localized
        // "Anonymous" strings / autolink HTML) to return raw stored values.
        $raw = $this->resource->getAttributes();

        $data = [
            'id' => $this->id,
            'rel_type' => $this->rel_type,
            'rel_id' => $this->rel_id,
            'comment_subject' => $this->comment_subject,
            'comment_body' => $raw['comment_body'] ?? null,
            'comment_name' => $raw['comment_name'] ?? null,
            'comment_website' => $this->comment_website,
            'reply_to_comment_id' => $this->reply_to_comment_id,
            'is_moderated' => (bool) $this->is_moderated,
            'is_new' => (bool) $this->is_new,
            'is_spam' => (bool) $this->is_spam,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];

        if ($isAdmin) {
            $data['comment_email'] = $raw['comment_email'] ?? null;
            $data['user_ip'] = $this->user_ip;
            $data['session_id'] = $this->session_id;
            $data['user_agent'] = $this->user_agent;
            $data['is_reported'] = (bool) $this->is_reported;
        }

        return $data;
    }
}
