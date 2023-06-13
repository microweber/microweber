<?php

namespace MicroweberPackages\Modules\Comments\Policies;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Modules\Comments\Models\Comment;
use MicroweberPackages\User\Models\User;

class CommentPolicy
{

    public function update(User $user, Comment $comment): bool
    {
        if ($user and $user->id == $comment->created_by) {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($comment->user_ip == user_ip()
            && $comment->session_id == session_id()) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user and $user->id == $comment->created_by) {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

}
