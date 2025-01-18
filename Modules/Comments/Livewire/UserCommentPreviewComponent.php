<?php

namespace Modules\Comments\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Comments\Models\Comment;
use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;

class UserCommentPreviewComponent extends Component
{
    use AuthorizesEditCommentsRequests;

    public Comment $comment;
    public bool $showUserAvatar = true;
    public bool $allowReplies = true;

    public function mount(Comment $comment, bool $showUserAvatar = true, bool $allowReplies = true)
    {
        $this->comment = $comment;
        $this->showUserAvatar = $showUserAvatar;
        $this->allowReplies = $allowReplies;
    }

    public function showDeleteModal()
    {
        if ($this->authorizeCheck('delete', $this->comment)) {
            $this->dispatch('openModal', 'comments::modals.delete-modal', [
                'commentId' => $this->comment->id
            ]);
        }
    }

    #[On('deleteComment')]
    public function deleteComment(int $commentId)
    {
        if ($this->comment->id === $commentId && $this->authorizeCheck('delete', $this->comment)) {
            $this->comment->deleteWithReplies();
            $this->dispatch('commentDeleted', commentId: $commentId);
            $this->dispatch('refreshCommentsList')->to('comments::user-comment-list');
        }
    }

    public function showReplyModal()
    {
        $this->dispatch('openModal', 'comments::modals.reply-modal', [
            'relId' => $this->comment->rel_id,
            'relType' => $this->comment->rel_type,
            'replyToCommentId' => $this->comment->id
        ]);
    }


    public function showEditModal()
    {

        if ($this->authorizeCheck('update', $this->comment)) {

            $this->dispatch('openModal', 'comments::modals.edit-modal', [
                'commentId' => $this->comment->id
            ]);
        }
    }
    public function render()
    {
        return view('modules.comments::livewire.user-comment-preview');
    }
}
