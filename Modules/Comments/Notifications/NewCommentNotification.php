<?php

namespace Modules\Comments\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use MicroweberPackages\Notification\Channels\AppMailChannel;
use Modules\Comments\Models\Comment;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable): array
    {
        return ['database', AppMailChannel::class]; //return [AppMailChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        $contentTitle = $this->comment->contentTitle();
        $commentUrl = $this->comment->content?->link() . '#comment-' . $this->comment->id;

        return (new MailMessage)
            ->subject('New Comment Posted')
            ->greeting('Hello Admin!')
            ->line('A new comment has been posted on: ' . $contentTitle)
            ->line('Comment by: ' . $this->comment->comment_name)
            ->line('Comment: ' . $this->comment->comment_body)
            ->action('View Comment', $commentUrl)
            ->line('You can moderate this comment from the admin panel.');
    }
}
