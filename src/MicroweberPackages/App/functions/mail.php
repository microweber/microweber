<?php

function get_email_from($group = 'email')
{
    $emailFrom = mw()->option_manager->get('email_from', $group);


    return $emailFrom;
}
