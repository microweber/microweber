<?php
namespace MicroweberPackages\Modules\Comments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\CommonMark\CommonMarkConverter;
use MicroweberPackages\Content\Models\Content;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'comments';

    public $fillable = [
        'rel_id',
        'rel_type',
        'reply_to_comment_id',
        'comment_name',
        'comment_body',
        'comment_email',
        'comment_website',
    ];

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
        if ($this->is_new == 1 && $this->is_moderated == 0) {
            return true;
        }
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
        $content = $this->content()->select('title')->first();
        if ($content) {
            return $content->title;
        } else {
            return 'No post title';
        }
    }

    public function getCommentNameAttribute()
    {
        if ($this->attributes['created_by'] > 0) {
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

    public function getCommentBodyAttribute()
    {
        return app()->format->autolink($this->attributes['comment_body']);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'reply_to_comment_id');
    }

    public function parentComment()
    {
        return $this->belongsTo(Comment::class, 'reply_to_comment_id');
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
