<?php


/* SUBSCRIBER FUNCTIONS */


function newsletter_unsubscribe($params)
{
    //code...
}

function newsletter_subscribe($params)
{
    //code...
}


function newsletter_get_subscribers($params)
{
    if (is_string($params)) {
        $params = parse_params($params);
    }
    $params['table'] = "newsletter_subscribers";
    return db_get($params);
}

api_expose_admin('newsletter_save_subscriber');
function newsletter_save_subscriber($data)
{

    $table = "newsletter_subscribers";

    if (!isset($data['is_subscribed']) and !isset($data['id'])) {
        $data['is_subscribed'] = 1;
    }
    return db_save($table, $data);
}

api_expose_admin('newsletter_delete_subscriber');
function newsletter_delete_subscriber($params)
{
    if (isset($params['id'])) {
        $table = "newsletter_subscribers";
        $id = $params['id'];
        return db_delete($table, $id);
    }
}


/* EMAIL CAMPAIGN FUNCTIONS */

function newsletter_save_campaign($params)
{
    //code...
}


function newsletter_delete_campaign($params)
{
    //code...
}


function newsletter_send_campaign($params)
{
    //code...
}


