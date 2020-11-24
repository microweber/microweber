<?php

namespace MicroweberPackages\Comment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use InteractsWithQueue, SerializesModels;

    public $data;
    public $notification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data = false)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->data;
    }

    public function setNotification($noification)
    {
        $this->notification = $noification;
    }

    public function message()
    {
        $notification = $this->notification->data;

        if (!isset($notification['id'])) {
            return false;
        }

        $article = false;
        $comments = false;
        $picture = thumbnail(false);
        if (isset($notification['rel_id'])) {

            $article = get_content_by_id($notification['rel_id']);
            $comments = get_comments('rel_type='.$notification['rel_type'].'&rel_id=' . $notification['rel_id']);
            $picture = get_picture($article['id'],$notification['rel_type']);

            if (isset($picture['filename'])) {
                $picture = $picture['filename'];
                $picture = thumbnail($picture);
            }

        }

        $created_by = false;
        if (isset($notification['created_by'])) {
            $created_by = get_user_by_id($notification['created_by']);
            $created_by_username = $created_by['username'];
            $user_picture = user_picture($created_by);
        }

        $toView = [];
        $toView['id'] = $this->notification->id;
        $toView['notification'] = $notification;
        $toView['article'] = $article;
        $toView['comments'] = $comments;
        $toView['picture'] = $picture;
        $toView['user_picture'] = $user_picture;
        $toView['created_by'] = $created_by;
        $toView['created_by_username'] = $created_by_username;

        return view('comment::admin.notifications.new_comment',$toView);
    }
}
