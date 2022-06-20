<?php

use MicroweberPackages\Option\Facades\Option;

function get_email_from($group = 'email')
{
    if(get_email_transport() == 'config'){
        return \Illuminate\Support\Facades\Config::get('mail.from.address');
    }

   return mw()->option_manager->get('email_from', $group);
}

function get_email_from_name($group = 'email')
{
    if(get_email_transport() == 'config'){
        return \Illuminate\Support\Facades\Config::get('mail.from.name');
    }

    return mw()->option_manager->get('email_from_name', $group);
}

function get_email_transport()
{
    return Option::getValue('email_transport', 'email');
}
