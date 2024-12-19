<?php

namespace Modules\Comments\Services;

use Modules\Comments\Models\Comment;

class CommentsManager
{
    public function get($params = [])
    {
        return Comment::all();
    }
}
