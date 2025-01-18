<?php

namespace Modules\Comments\Livewire\Modals;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Modules\Comments\Models\Comment;
use Modules\Comments\Livewire\AuthorizesEditCommentsRequests;

class DeleteModal extends ModalComponent
{
    use AuthorizesEditCommentsRequests;

    public ?Comment $comment = null;
    public $commentId;

    public function mount($commentId)
    {
        $this->commentId = $commentId;
        $this->comment = Comment::find($commentId);
    }

    public function delete()
    {
        if ($this->comment && $this->authorizeCheck('delete', $this->comment)) {
            $this->dispatch('deleteComment', commentId: $this->comment->id);
            $this->dispatch('closeModal');
        }
    }

    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public function render()
    {
        return view('modules.comments::livewire.modals.delete-modal');
    }
}
