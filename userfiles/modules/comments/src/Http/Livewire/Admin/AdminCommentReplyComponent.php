<?php

namespace MicroweberPackages\Modules\Comments\Http\Livewire\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use MicroweberPackages\Modules\Comments\Http\Livewire\UserCommentReplyComponent;

class AdminCommentReplyComponent extends UserCommentReplyComponent
{
    use AuthorizesRequests;

    public $view = 'comments::admin.livewire.admin-comment-reply-component';

    public function __construct($id = null)
    {
        try {
            $this->authorize('isAdmin');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(401, 'Unauthorized action.');
        }

        parent::__construct($id);

    }
}

