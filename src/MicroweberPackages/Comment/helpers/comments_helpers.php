<?php


if (!function_exists('get_comments')) {

    function get_comments($params = false)
    {
        $comments = new  \MicroweberPackages\Comment\Models\CommentsCrud();

        return $comments->get($params);
    }
}

