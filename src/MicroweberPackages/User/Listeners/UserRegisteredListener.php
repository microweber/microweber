<?php

namespace MicroweberPackages\User\Listeners;


use MicroweberPackages\User\Notifications\NewRegistration;

class UserRegisteredListener
{
    public function handle($event)
    {
        $event->user->notifyNow(new NewRegistration());
//        try {
//            $event->user->notify(new NewRegistration());
//        } catch (\Exception $e) {
//
//        }


//        if (isset($event->user)) {
//
//            $notif = array();
//            $notif['module'] = 'users';
//            $notif['rel_type'] = 'users';
//            $notif['rel_id'] = $event->user->id;
//            $notif['title'] = 'New user registration';
//            $notif['description'] = 'You have new user registration';
//            $notif['content'] = 'You have new user registered with the username [' . $event->user->username . '] and id [' . $event->user->id . ']';
//
//            mw()->notifications_manager->save($notif);
//            mw()->log_manager->save($notif);
//            mw()->event_manager->trigger('mw.user.after_register', $event->user);
//        }

    }
}
