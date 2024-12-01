<?php

namespace Modules\Cart\Listeners;
use Illuminate\Support\Facades\Session;

class UserLoginListener
{

    public function handle($event)
    {
        $oldSid = Session::get('old_sid');

        if(!empty($oldSid)) {
            \Modules\Cart\Models\Cart::where('session_id', $oldSid)->update(array('session_id' => Session::getId()));
            mw()->cache_manager->delete('cart');
        }
    }
}
