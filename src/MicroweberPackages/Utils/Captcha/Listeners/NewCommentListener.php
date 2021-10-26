<?php

namespace MicroweberPackages\Utils\Captcha\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewCommentListener
{

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
       // reset the captcha
       app()->captcha_manager->reset();
    }
}
