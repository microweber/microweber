<?php

namespace MicroweberPackages\Modules\Comments\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;
use MicroweberPackages\Modules\Comments\Models\Comment;

class UserCommentPreviewComponent extends Component {

    use AuthorizesRequests;

    public $commentId;
    public $state = [];
    public $showReplies = false;
    public $editForm = false;

    protected $listeners = [
        'commentAdded' => 'refreshIfReplyIsToMe',
        'commentDeleted' => 'refreshIfReplyIsToMe',
        'commentUpdated' => 'refreshIfReplyIsToMe',
    ];

    public function mount($commentId = false)
    {
        $this->commentId = $commentId;
    }

    public function edit()
    {
        $getComment = Comment::where('id', $this->commentId)->first();

        $this->authorize('update', $getComment);

        $this->editForm = true;
    }

    public function refreshIfReplyIsToMe($id) {
        if ($id == $this->commentId) {
            $this->showReplies = true;
            $this->emit('$refresh');
        }
    }

    public function delete()
    {
       $getComment = Comment::where('id', $this->commentId)->first();

       $this->authorize('delete', $getComment);

       $this->emit('deleteComment', $this->commentId);
    }

    public function render()
    {
        $getComment = Comment::where('id', $this->commentId)->first();
        $this->state = $getComment->toArray();

        return view('comments::livewire.user-comment-preview-component', [
            'comment' => $getComment,
        ]);

    }

}
