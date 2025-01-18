<?php

namespace Modules\Comments\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Comments\Models\Comment;
use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;

class UserCommentPreviewComponent extends Component
{
    use AuthorizesRequests;

    public Comment $comment;
    public bool $showUserAvatar = true;
    public bool $allowReplies = true;

    public function mount(Comment $comment, bool $showUserAvatar = true, bool $allowReplies = true)
    {
        $this->comment = $comment;
        $this->showUserAvatar = $showUserAvatar;
        $this->allowReplies = $allowReplies;
    }

    #[On('deleteComment')]
    public function deleteComment($commentId)
    {
        if ($this->comment->id === $commentId && $this->authorize('delete', $this->comment)) {
            $this->comment->deleteWithReplies();
            $this->dispatch('commentDeleted', $commentId);
        }
    }

    #[On('showReplyForm')]
    public function showReplyForm($commentId)
    {
        if ($this->comment->id === $commentId) {
            $this->dispatch('replyTo', ['id' => $commentId, 'body' => $this->comment->comment_body]);
        }
    }

    public function render()
    {
        return view('modules.comments::livewire.user-comment-preview');
    }
}
