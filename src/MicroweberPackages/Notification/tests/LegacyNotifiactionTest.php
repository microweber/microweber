<?php

namespace MicroweberPackages\User\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Utils\Mail\MailSender;


class LegacyNotifiactionTest extends TestCase
{

    public function testLegacyNotification()
    {



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
