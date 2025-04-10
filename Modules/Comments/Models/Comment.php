<?php

namespace Modules\Comments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use League\CommonMark\CommonMarkConverter;
use Modules\Content\Models\Content;

class Comment extends Model
{
    use Notifiable;
    protected $table = 'comments';
    protected $fillable = [
        'comment_subject',
        'comment_name',
        'comment_email',
        'comment_website',
        'comment_body',
        'rel_type',
        'rel_id',
        'reply_to_comment_id',
        'is_moderated',
        'is_new',
        'is_spam',
        'user_ip',
        'session_id',
        'created_by'
    ];

    public function isSpam()
    {
        if ($this->is_spam == 1) {
            return true;
        }
        
        // Check for common spam patterns
        $spamKeywords = ['viagra', 'casino', 'loan', 'credit', 'click here'];
        foreach ($spamKeywords as $keyword) {
            if (stripos($this->comment_body, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    public function shouldNotifyParent()
    {
        return $this->reply_to_comment_id 
            && config('modules.comments.notify_on_reply') 
            && !$this->isSpam();
    }

    public static function booted()
    {
        static::saving(function ($comment) {
            foreach ($comment->getAttributes() as $key => $value) {
                if (is_string($value)) {
                    if ($key == 'comment_body') {
                        continue;
                    }
                    $comment->{$key} = app()->format->clean_xss($value);
                }
            }
        });
    }

    public function commentBodyDisplay()
    {
        // Save markdown
        $renderer = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $commentBody = $renderer->convert($this->comment_body);

        return $commentBody;
    }

    public function isPending() {
        return $this->is_new == 1 && $this->is_moderated == 0;
    }

    public function scopePending($query)
    {
        return $query->where('is_new', 1)->where('is_moderated', 0);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_moderated', 1);
    }

    public function scopeSpam($query)
    {
        return $query->where('is_spam', 1);
    }

    public function scopeForAdminPreview($query)
    {
        return $query
            ->where(function ($subQuery) {
                $subQuery->where('is_spam', 0);
                $subQuery->orWhereNull('is_spam');
            });
    }

    public function scopePublished($query)
    {
        return $query
            ->where('is_moderated', 1)
            ->where(function ($subQuery) {
                $subQuery->where('is_spam', 0);
                $subQuery->orWhereNull('is_spam');
            });
    }

    public function content()
    {
        return $this->hasOne(Content::class,'id','rel_id');
    }

    public function contentId()
    {
        $content = $this->content()->select('id')->first();
        if ($content) {
            return $content->id;
        }
    }

    public function contentTitle()
    {
        return $this->content()->select('title');
    }

    public function getCommentNameAttribute()
    {
        if (isset($this->attributes['created_by']) and $this->attributes['created_by'] > 0) {
            return user_name($this->attributes['created_by']);
        } else if (!empty($this->attributes['comment_name'])) {
            return $this->attributes['comment_name'];
        } else {
            return _e('Anonymous');
        }
    }

    public function getCommentEmailAttribute()
    {
        if ($this->attributes['created_by'] > 0) {
            return user_email($this->attributes['created_by']);
        } else if (!empty($this->attributes['comment_email'])) {
            return $this->attributes['comment_email'];
        } else {
            return _e('Anonymous');
        }
    }

    public function getAvatarUrl()
    {
        return app(\Modules\Comments\Services\AvatarProvider::class)->getAvatarUrl($this);
    }

    public function getCommentBodyAttribute()
    {
        return app()->format->autolink($this->attributes['comment_body']);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'reply_to_comment_id')->with('replies');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'reply_to_comment_id');
    }

    // Alias for parent() to maintain backward compatibility
    public function parentComment()
    {
        return $this->parent();
    }

    public function parentCommentBody() {
        $parentComment = $this->parentComment()->first();
        if ($parentComment) {
            return $parentComment->comment_body;
        }
    }

    public function getLevel()
    {
        $level = 0;
        $parent = $this->reply_to_comment_id;
        if ($parent) {
            while ($parent > 0) {
                $level++;
                $parent = Comment::select(['id', 'reply_to_comment_id'])->where('id', $parent)->value('reply_to_comment_id');
            }
        }
        return $level;
    }

    public function deleteWithReplies()
    {
        if(count($this->replies) > 0) {
            foreach ($this->replies as $reply) {
                $reply->deleteWithReplies();
            }
        }
        $this->delete();
    }

}
