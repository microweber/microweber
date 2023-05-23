<?php
namespace MicroweberPackages\Modules\Comments\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $fillable = [
        'rel_id',
        'rel_type',
        'reply_to_comment_id',
        'comment_name',
        'comment_body',
        'comment_email',
        'comment_website',
    ];

    public function getCommentBodyAttribute()
    {
        return app()->format->autolink($this->attributes['comment_body']);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'reply_to_comment_id');
    }

    public function getLeveL()
    {
        $level = 0;
        $parent = $this->reply_to_comment_id;
        if ($parent) {
            while ($parent > 0) {
                $level++;
                $parent = Comment::select(['id', 'reply_to_comment_id'])
                    ->where('id', $parent)->value('reply_to_comment_id');
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
