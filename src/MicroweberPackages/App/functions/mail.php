<?php

function get_email_from($group = 'email')
{
    $emailFrom = mw()->option_manager->get('email_from', $group);


    return $emailFrom;
}

function get_email_from_name($group = 'email')
{
    $emailFromName = mw()->option_manager->get('email_from_name', $group);


    return $emailFromName;
}
