<?php

namespace Microweber\Comments\Jobs;


use \Illuminate\Bus\Queueable;
use \Illuminate\Queue\SerializesModels;
use \Illuminate\Queue\InteractsWithQueue;
use \Illuminate\Contracts\Queue\ShouldQueue;

use \Illuminate\Foundation\Bus\Dispatchable;
use \Illuminate\Contracts\Queue\Job;
use \Microweber\Comments\Models\Comment as Comment;

use \Illuminate\Contracts\Mail\Mailer;


class SendEmailJob implements ShouldQueue
{
    // use Dispatchable, InteractsWithQueue, Queueable;
    use Dispatchable, InteractsWithQueue, Queueable;

    public $timeout = 120;


    private $comment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {


    }
}
