<?php

namespace MicroweberPackages\User\Listeners;


class UserRegisteredListener
{
    public function handle($event)
    {

        if (isset($event->user)) {

            $notif = array();
            $notif['module'] = 'users';
            $notif['rel_type'] = 'users';
            $notif['rel_id'] = $event->user->id;
            $notif['title'] = 'New user registration';
            $notif['description'] = 'You have new user registration';
            $notif['content'] = 'You have new user registered with the username [' . $event->user->username . '] and id [' . $event->user->id . ']';

            mw()->notifications_manager->save($notif);
            mw()->log_manager->save($notif);
            mw()->event_manager->trigger('mw.user.after_register', $event->user);
        }

    }
}
