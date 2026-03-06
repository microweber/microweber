<?php

namespace MicroweberPackages\Notification\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use MicroweberPackages\Utils\Mail\MailSender;


class LegacyNotifiactionTest extends TestCase
{

    #[Test]

    public function it_legacy_notification(): void {



        $notification = array();
        $notification['module'] = 'shop';
        $notification['rel_type'] = 'cart_orders';
        $notification['rel_id'] = 1;
        $notification['title'] = _e('You have new order', true);
        $notification['description'] = _e('New order is placed from ', true) . $this->app->url_manager->current(1);
        $notification['content'] = _e('New order in the online shop. Order id: ', true) ;
        app()->notifications_manager->save($notification);




    }



}
