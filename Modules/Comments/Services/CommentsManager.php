<?php

namespace Modules\Comments\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Comments\Models\Comment;

class CommentsManager
{
    public function get($params = [])
    {
        $query = Comment::query();

        // Filter by rel_type and rel_id if provided
        if (isset($params['rel_type'])) {
            $query->where('rel_type', $params['rel_type']);
        }
        if (isset($params['rel_id'])) {
            $query->where('rel_id', $params['rel_id']);
        }

        // Apply moderation filter if enabled
        if (module_option('comments', 'enable_moderation', true)) {
            $query->where('is_moderated', 1);
        }

        // Apply spam filter if enabled
        if (module_option('comments', 'enable_spam_filter', true)) {
            $query->where(function($q) {
                $q->whereNull('is_spam')
                  ->orWhere('is_spam', 0);
            });
        }

        // Apply sorting
        $sortOrder = module_option('comments', 'sort_order', 'newest');
        switch ($sortOrder) {
            case 'oldest':
                $query->oldest();
                break;
            case 'most_liked':
                $query->orderBy('likes_count', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // Apply pagination
        $perPage = module_option('comments', 'comments_per_page', 10);
        return $query->paginate($perPage);
    }

    public function create($data)
    {
        // Check if login is required
        if (module_option('comments', 'require_login', false) && !Auth::check()) {
            throw new \Exception('Login required to post comments');
        }

        $comment = new Comment();
        $comment->rel_type = $data['rel_type'] ?? null;
        $comment->rel_id = $data['rel_id'] ?? null;
        $comment->comment_subject = $data['subject'] ?? null;
        $comment->comment_body = $data['body'];
        $comment->comment_name = $data['name'] ?? Auth::user()?->name;
        $comment->comment_email = $data['email'] ?? Auth::user()?->email;
        $comment->comment_website = $data['website'] ?? null;
        $comment->reply_to_comment_id = $data['reply_to'] ?? null;
        $comment->created_by = Auth::id();
        $comment->session_id = session()->getId();
        $comment->user_ip = request()->ip();
        $comment->user_agent = request()->userAgent();

        // Set moderation status
        if (module_option('comments', 'enable_moderation', true)) {
            $comment->is_moderated = 0; // Requires moderation
        } else {
            $comment->is_moderated = 1; // Auto-approve
        }

        $comment->save();
        return $comment;
    }

    public function update($id, $data)
    {
        $comment = Comment::findOrFail($id);

        if (isset($data['body'])) {
            $comment->comment_body = $data['body'];
        }
        if (isset($data['is_moderated'])) {
            $comment->is_moderated = $data['is_moderated'];
        }
        if (isset($data['is_spam'])) {
            $comment->is_spam = $data['is_spam'];
        }

        $comment->save();
        return $comment;
    }

    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        return $comment->delete();
    }

    public function markAsSpam($id)
    {
        return $this->update($id, ['is_spam' => 1]);
    }

    public function approve($id)
    {
        return $this->update($id, ['is_moderated' => 1]);
    }


}
