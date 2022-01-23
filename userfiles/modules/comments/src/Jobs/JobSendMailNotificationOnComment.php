<?php

namespace Microweber\Comments\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
//use Microweber\Comments\Models\Comment as Comment;
//use Microweber\App\Jobs\JobSendEmail;


class JobSendMailNotificationOnComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;

    private $commentId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($commentId)
    {
        $this->commentId = $commentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
// @todo fix it
        return;
        $newComment = get_comments('single=1&id=' . $this->commentId);
        if (empty($newComment)) {
            echo 'No comment found.';
            return;
        }

        $newCommentMailTemplateId = mw()->option_manager->get('new_comment_reply_email_template', 'comments');
        $mailTemplate = get_mail_template_by_id($newCommentMailTemplateId, 'new_comment_reply');

        $comments = get_comments('content_id=' . $newComment['rel_id']);

        $commentsMailMap = array();
        foreach ($comments as $comment) {

            $emailTo = $comment['comment_email'];

            if (array_key_exists($emailTo, $commentsMailMap)) {
                continue;
            }

            $commentsMailMap[$emailTo] = $comment;

            try {
                 $twig = new \MicroweberPackages\View\TwigView();

                $commentEmailContent = $twig->render(
                    $mailTemplate['message'],
                    array('comment_author' => $comment['comment_name'], 'comment_reply_author' => $newComment['comment_name'], 'post_url' => $comment['from_url'])
                );

                if (isset($emailTo) and (filter_var($emailTo, FILTER_VALIDATE_EMAIL))) {

                    $jobSendEmail = new JobSendEmail();
                    $jobSendEmail->setEmailTo($emailTo);
                    $jobSendEmail->setEmailSubject($mailTemplate['subject']);
                    $jobSendEmail->setEmailMessage($commentEmailContent);
                    $jobSendEmail->setEmailFrom($mailTemplate['from_email']);
                    $jobSendEmail->setEmailFromName($mailTemplate['from_name']);
                    $jobSendEmail->onQueue('processing');

                    \Queue::later(3, $jobSendEmail);

                    // Mark as sent
                    mw()->database_manager->save('comments', array(
                        'is_sent_email' => 1,
                        'id' => $comment['id']
                    ));

                    return true;
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

    }
}
