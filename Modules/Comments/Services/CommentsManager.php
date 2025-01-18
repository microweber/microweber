<?php

namespace Modules\Comments\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Modules\Comments\Models\Comment;
use Modules\Comments\Notifications\NewCommentNotification;
use Modules\Comments\Notifications\CommentReplyNotification;

class CommentsManager
{
    public function getConfig($key = null)
    {
        $value = module_option('comments', $key, config('modules.comments.' . $key));
        if ($value === 'true') {
            return true;
        } elseif ($value === 'false') {
            return false;
        } else {
            return $value;
        }
    }

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
        if ($this->getConfig('enable_moderation')) {
            $query->where('is_moderated', 1);
        }

        // Apply spam filter if enabled
        if ($this->getConfig('block_spam_keywords')) {

//            $query->where(function ($q) {
//                $q->whereNull('is_spam')
//                    ->orWhere('is_spam', 0);
//            });
        }

        // Apply sorting
        $sortOrder = $this->getConfig('sort_order');
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
        $perPage = module_option('comments', 'comments_per_page', config('modules.comments.comments_per_page'));
        return $query->paginate($perPage);
    }

    public function create($data)
    {
        // Check if comments are enabled globally
        if (!$this->getConfig('enable_comments')) {
            throw new \Exception('Comments are currently disabled');
        }

        // Check if guest comments are allowed
        if (!$this->getConfig('allow_guest_comments') && !Auth::check()) {
            throw new \Exception('Only registered users can post comments');
        }

        // Validate comment length
        $minLength = $this->getConfig('min_comment_length');
        $maxLength = $this->getConfig('max_comment_length');
        $commentLength = Str::length($data['body']);

        if ($commentLength < $minLength) {
            throw new \Exception("Comment must be at least {$minLength} characters long");
        }
        if ($commentLength > $maxLength) {
            throw new \Exception("Comment cannot exceed {$maxLength} characters");
        }

        // Check if replies are allowed
        if (isset($data['reply_to']) && !module_option('comments', 'allow_replies', config('modules.comments.allow_replies'))) {
            throw new \Exception('Replies are currently disabled');
        }

        // Check for spam keywords if enabled
        if ($this->getConfig('block_spam_keywords')) {
            $spamKeywords = explode(',', $this->getConfig('spam_keywords'));
            $spamKeywords = array_map('trim', $spamKeywords);

            foreach ($spamKeywords as $keyword) {
                if (!empty($keyword) && Str::contains(strtolower($data['body']), strtolower($keyword))) {
                    throw new \Exception('This comment has been identified as potential spam');
                }
            }
        }

        // Check maximum links if enabled
        $maxLinks = $this->getConfig('max_links');
        if ($maxLinks > 0) {
            $linkCount = substr_count(strtolower($data['body']), 'http');
            if ($linkCount > $maxLinks) {
                throw new \Exception("Maximum {$maxLinks} links allowed per comment");
            }
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
        if ($this->getConfig('enable_moderation')) {
            $comment->is_moderated = 0; // Requires moderation
        } else {
            $comment->is_moderated = 1; // Auto-approve
        }

        $comment->save();

        // Send email notifications if enabled
        if ($this->getConfig('notify_admin')) {
            $adminEmail = $this->getConfig('admin_email');
            if ($adminEmail) {
                Notification::route('mail', $adminEmail)
                    ->notify(new NewCommentNotification($comment));
            }
        }

        // Notify users of replies if enabled
        if (isset($data['reply_to']) && $this->getConfig('notify_users')) {
            $parentComment = Comment::find($data['reply_to']);
            if ($parentComment && $parentComment->comment_email) {
                Notification::route('mail', $parentComment->comment_email)
                    ->notify(new CommentReplyNotification($comment, $parentComment));
            }
        }

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
