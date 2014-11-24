<?php



function is_page()
{
    if (page_id()) {
        return true;
    }
}

function is_post()
{
    if (post_id()) {
        return true;
    }
}

function is_category()
{
    if (category_id()) {
        return true;
    }
}

function page_id()
{
    if (defined('PAGE_ID')) {
        return PAGE_ID;
    }
}

function post_id()
{
    if (defined('POST_ID')) {
        return POST_ID;
    }
}

function content_id()
{
    if (post_id()) {
        return post_id();
    } elseif (page_id()) {
        return page_id();
    }
}


function category_id()
{
    if (defined('CATEGORY_ID')) {
        return CATEGORY_ID;
    }
}
