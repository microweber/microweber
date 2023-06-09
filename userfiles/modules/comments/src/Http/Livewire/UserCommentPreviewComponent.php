<?php

namespace MicroweberPackages\Modules\Comments\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Comments\Models\Comment;

class UserCommentPreviewComponent extends Component {

    public $commentId;
    public $state = [];
    public $showReplies = false;

    protected $listeners = [
        'commentAdded' => 'refreshIfReplyIsToMe',
        'commentDeleted' => 'refreshIfReplyIsToMe',
        'commentUpdated' => 'refreshIfReplyIsToMe',
    ];

    public function mount($commentId = false)
    {
        $this->commentId = $commentId;
    }

    public function refreshIfReplyIsToMe($id) {
        if ($id == $this->commentId) {
            $this->showReplies = true;
            $this->emit('$refresh');
        }
    }

    public function delete()
    {
       $this->emit('deleteComment', $this->commentId);
    }

    public function render()
    {
        $getComment = Comment::where('id', $this->commentId)->first();

        return view('comments::livewire.user-comment-preview-component', [
            'comment' => $getComment,
        ]);

    }

}
