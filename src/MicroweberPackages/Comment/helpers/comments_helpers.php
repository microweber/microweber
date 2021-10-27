<?php


function get_comments($params = false)
{
    $comments = new  \MicroweberPackages\Comment\Models\CommentsCrud();

    return $comments->get($params);
}
