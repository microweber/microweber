<?php

namespace Modules\Comments\Livewire;

use Illuminate\Support\Facades\Session;
use Modules\Comments\Models\Comment;

trait AuthorizesEditCommentsRequests
{
    /**
     * Authorize a given action for the current user.
     *
     * @param mixed $ability
     * @param mixed|array $arguments
      *
      */
    public function authorizeCheck($ability, $arguments = []) : bool
    {

        if ($ability == 'delete') {
            return $this->authorizeDelete($arguments);
        }
        if ($ability == 'update') {
            return $this->authorizeUpdate($arguments);
        }
        if ($ability == 'create') {
            return $this->authorizeCreate($arguments);
        }
        return false;
    }
    public function authorize($ability, $arguments = [])
    {
        return true;
    }
    protected function authorizeDelete($comment)
    {
        if (!$comment instanceof Comment) {
            return false;
        }

        if (auth()->check()) {
            if (auth()->id() === $comment->created_by) {
                return true;
            }
            if (auth()->user()->isAdmin()) {
                return true;
            }
        }

        if ($comment->user_ip === user_ip() && $comment->session_id === Session::getId()) {
            return true;
        }

        return false;
    }

    protected function authorizeUpdate($comment)
    {
        if (!$comment instanceof Comment) {
            return false;
        }

        if (auth()->check()) {
            if (auth()->id() === $comment->created_by) {
                return true;
            }
            if (auth()->user()->isAdmin()) {
                return true;
            }
        }

        if ($comment->user_ip === user_ip() && $comment->session_id === Session::getId()) {
            return true;
        }

        return false;
    }

    protected function authorizeCreate()
    {
        // By default allow creating comments unless specific restrictions are needed
        return true;
    }
}
