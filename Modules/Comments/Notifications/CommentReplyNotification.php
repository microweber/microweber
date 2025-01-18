<?php

namespace Modules\Comments\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Comments\Models\Comment;

class CommentReplyNotification extends Notification
{
    use Queueable;

    protected $comment;
    protected $parentComment;

    public function __construct(Comment $comment, Comment $parentComment)
    {
        $this->comment = $comment;
        $this->parentComment = $parentComment;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $contentTitle = $this->comment->contentTitle();
        $commentUrl = $this->comment->content?->url() . '#comment-' . $this->comment->id;

        return (new MailMessage)
            ->subject('New Reply to Your Comment')
            ->greeting('Hello ' . $this->parentComment->comment_name . '!')
            ->line('Someone has replied to your comment on: ' . $contentTitle)
            ->line('Your comment: ' . $this->parentComment->comment_body)
            ->line('Reply by ' . $this->comment->comment_name . ':')
            ->line($this->comment->comment_body)
            ->action('View Reply', $commentUrl)
            ->line('Thank you for participating in the discussion!');
    }
}
