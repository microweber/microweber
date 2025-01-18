<?php

namespace Modules\Comments\Livewire;

use Livewire\Component;
use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;

class UserCommentPreviewComponent extends Component
{

    use AuthorizesRequests;

    public $comment = [];
    public $showReplies = false;
    public $editForm = false;
    public $editText = '';

    protected $listeners = [
        'commentAdded' => 'refreshIfReplyIsToMe',
        'commentDeleted' => 'refreshIfReplyIsToMe',
        'commentUpdated' => 'refreshIfReplyIsToMe',
    ];

    public function edit()
    {
        if (!$this->authorize('update', $this->comment)) {
            return;
        }

        $this->editText = $this->comment->comment_body;
        $this->editForm = true;
    }

    public function save()
    {
        if (!$this->authorize('update', $this->comment)) {
            return;
        }

        $this->validate(['editText' => 'required']);

        $this->comment->update([
            'comment_body' => $this->editText,
        ]);

        $this->editForm = false;
    }

    public function refreshIfReplyIsToMe($id)
    {
        if ($id == $this->comment->id) {
            $this->showReplies = true;
            $this->dispatch('$refresh')->self();
        }
    }

    public function delete()
    {
        if (!$this->authorize('delete', $this->comment)) {
            return;
        }

        $this->dispatch('deleteComment', $this->comment->id);
    }

    public function render()
    {
        return view('modules.comments::livewire.user-comment-preview-component');
    }

    public function authorize($ability, $arguments = [])
    {
        if ($ability == 'update') {
            if ($arguments->created_by == user_id() || is_admin()) {
                return true;
            }
        }
        if ($ability == 'delete') {
            if ($arguments->created_by == user_id() || is_admin()) {
                return true;
            }
        }
        if ($ability == 'create') {
            return true;
        }
        if ($ability == 'view') {
            return true;
        }
    }

}
