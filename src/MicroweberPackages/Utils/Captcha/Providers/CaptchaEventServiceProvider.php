<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Utils\Captcha\Providers;


use Events\NewComment;
use MicroweberPackages\App\Providers\EventServiceProvider;
use MicroweberPackages\Utils\Captcha\Listeners\NewCommentListener;

class CaptchaEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        NewComment::class => [
            NewCommentListener::class
        ],
    ];
}

