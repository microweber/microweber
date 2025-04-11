<?php

namespace Modules\Comments\Policies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Modules\Comments\Models\Comment;
use MicroweberPackages\User\Models\User;

class CommentPolicy
{

    public function update(User|null $user, Comment $comment): bool
    {
        // Admin checks (bypass all restrictions)
        if ($user && $user->isAdmin()) {
            return true;
        }

        // Creator can always update their own comments
        if ($user && $user->id == $comment->created_by) {
            return true;
        }

        // Session/IP validation (for any comment with matching credentials)
        if ($comment->user_ip == user_ip()
            && $comment->session_id == Session::getId()) {
            return true;
        }

        return false;
    }

    public function delete(User|null $user, Comment $comment): bool
    {
        if ($user) {
            if ($user->id == $comment->created_by) {
                return true;
            }

            if ($user->isAdmin()) {
                return true;
            }
        }

        if ($comment->user_ip == user_ip()
            && $comment->session_id == Session::getId()) {
            return true;
        }

        return false;
    }

}
